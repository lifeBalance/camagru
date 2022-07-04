<?php

class Img
{
    static function merge($user_file, $stickers)
    {
        // Turn the 'stickers' string (name and coordinates) into array.
        $stickers = json_decode($stickers);

        // Create  GdImage instance out of the user's image
        $usr_gdi = imagecreatefrompng($user_file);
        // Compute dimensions for the user's image
        $usr_width = imagesx($usr_gdi);
        $usr_height = imagesy($usr_gdi);

        // Create a black canvas with the specified size. 
        $canvas = imagecreatetruecolor($usr_width, $usr_height);
        // Save the transparency information in the black canvas
        imagesavealpha($canvas, true);
        // Create a fully transparent background
        $alpha = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        // Fill the canvas with a transparent background
        imagefill($canvas, 0, 0, $alpha);

        // Copy the user's GdI object on the canvas
        imagecopy($canvas, $usr_gdi, 0, 0, 0, 0, $usr_width, $usr_height);

        // Copy the stickers to the canvas
        foreach ($stickers as $sticker) {
            $name       = $sticker[0];
            $dst_x      = preg_replace("/[^0-9]/", '', $sticker[1]);
            $dst_y      = preg_replace("/[^0-9]/", '', $sticker[2]);
            $src_width  = $sticker[3];
            $src_height = $sticker[4];

            // Load sticker file
            $stickers_folder = PUBLIC_DIR . '/assets/stickers';
            $sticker_file = $stickers_folder . "/$name.png";

            // Create sticker's GdImage instance
            $sticker_gdi = Img::resizeSticker(
                $sticker_file, $src_width, $src_height);

            // Copy the sticker to the canvas
            imagecopy(
                $canvas,
                $sticker_gdi,
                $dst_x,     // X coord. where we want to start pasting
                $dst_y,     // Y coord. where we want to start pasting
                0,          // STARTING x coord. of sticker
                0,          // STARTING y coord. of sticker
                $src_width, // Width of the sticker
                $src_height // Height of the sticker
            );
        }
        // Store the result as a file in the uploads folder
        imagepng($canvas, $user_file);

        // Destroy canvas once the file is written
        imagedestroy($canvas);
    }

    static function url_profile_pic($user_id)
    {
        require_once(APPROOT .'/models/User.class.php');
        $user = new User();

        $user->findById($user_id);
        if (empty($user->profile_pic))
            return URLROOT . "/assets/no_profile_pic.png";
        else
            return URLROOT . "/assets/$user->profile_pic.png";
    }

    /**
     * Resize a sticker preserving the transparency.
     * 
     * @param   String  Path to image file.
     * @return  GdImage GdImage object.
     */
    static function resizeSticker($file, int $newWidth, int $newHeight) {
        $img = imagecreatefrompng($file);
        $blank = imagecreatetruecolor($newWidth, $newHeight);
        $transparent = imagecolorallocatealpha($blank, 0, 0, 0, 127);
        imagefill($blank, 0, 0, $transparent);
        imagecolortransparent($blank, $transparent);
        imagecopyresampled($blank, $img, 0, 0, 0, 0, $newWidth, $newHeight, 128, 128);
        imagepng($blank);
        return $blank;
    }

    static function get_extension($img)
    {
        $mime_type = image_type_to_mime_type(exif_imagetype($img));
        return substr($mime_type, strpos($mime_type, "/") + 1);
    }
}