document.addEventListener('DOMContentLoaded', function () {
  // Select 'file input' element.
  const fileInput = document.getElementById('fileInput');
  // Select 'div' element (parent of 'canvas' preview).
  const preview = document.getElementById('preview');
  // Create 'canvas' element.
  let   canvas  = document.createElement('canvas');
  let   img     = new Image();
  let   ctx     = canvas.getContext('2d');
  let   reader  = new FileReader();
  
  // Add event listener/handler to 'file input' element.
  fileInput.addEventListener('change', function (e) {
    reader.onload = function(event){
        img.onload = function(){
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img,0,0);
        }
        img.src = event.target.result;
    }
    reader.readAsDataURL(e.target.files[0]);
    preview.appendChild(canvas);
  });
}); // End of 'DOMContentLoaded' event listener/handler
