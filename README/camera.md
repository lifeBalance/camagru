# Dealing with the Webcam
PHP is a server side language that doesn't give us access to the client-side hardware. In order to access the user's webcam we have to use JavaScript.

## Showing the Video Stream
In order to show what the user's webcam is recording we'll need two things:

1. Access to the video stream itself, which in JavaScript is available thanks to the [MediaDevices](https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices) browser API. This interface allows us to access any connected hardware such as cameras and microphones, as well as screen sharing.

2. An HTML [video](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/video) element in our markup where we'll show the video stream from the webcam.

The `video` element has several attributes available, but we're interested in [autoplay](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/video#attr-autoplay), since we want the webcam stream to show up ASAP.

> `autoplay` is a boolean attribute that controls if the video automatically begins to play back as soon as it can do so. Note that to set this attribute as **true** we just have to include the word `autoplay` in our `video` tag. To set it as false, we just have to remove it.

To tie up together the `video` element and the **stream** coming from our webcam, we need to write a function in our JavaScript (I called it `startStream`). This must be an [async](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/async_function) function because we'll have to wait for the [getUserMedia](https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia) (one of the methods available in the [MediaDevices](https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices), which is one of the **properties** of the [Navigator](https://developer.mozilla.org/en-US/docs/Web/API/Navigator) API) to get access to the user's webcam stream. Once that stream is available, we just use another function (which I called `handleStream`) to tie up the `video` element to the webcam stream.

> We're using [srcObject](https://developer.mozilla.org/en-US/docs/Web/API/HTMLMediaElement/srcObject) to set content of our `video` element to the MediaStream directly.

## Capturing the Snapshot
Once the video stream from our webcam is showing up in the `video` element, we need a way of capturing a video frame and place it in a [canvas](https://developer.mozilla.org/en-US/docs/Web/API/HTMLCanvasElement) element, where we'll be able to edit it (place stickers), and ultimately, upload it to our server. For that we'll need:

* A [form]() element, with no attributes other than a hardcoded [action](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/form#attr-action) and an [id]()l
* A nested [input submit](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/submit) button, which we'll listen to trigger a [fetch]() request to upload the image from the canvas. 

These are the steps I took:

1. When the `Submit` button is clicked, we use the [toDataURL](https://developer.mozilla.org/en-US/docs/Web/API/HTMLCanvasElement/toDataURL) method to extract the data from the canvas and store in a variable (`imageData`).
2. Then we create a new [FormData](https://developer.mozilla.org/en-US/docs/Web/API/FormData) object, and [append](https://developer.mozilla.org/en-US/docs/Web/API/FormData/append) to it the `imageData` under a **key** which I named `img`.
3. Then we use the [fetch](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API) API to send the `formData` object to the server.

> The first argument to `fetch` must be the URL where we want to send our request. I got that from the HTML markup, using the [getAttribute](https://developer.mozilla.org/en-US/docs/Web/API/Element/getAttribute) method.

## Server Side
After posting the screenshot using `fetch`, we just have to invoke [file_put_contents](https://www.php.net/manual/en/function.file-put-contents.php) with [file_get_contents](https://www.php.net/manual/en/function.file-get-contents.php) as a second argument.

> Remember, `file_get_contents` needs `data:image/png;base64`, at the beginning of the data, or else will fail. (That's why we didn't trim it when appending the data to the `FormData` instance in our `fetch` operation.

---
[:arrow_backward:][back] ║ [:house:][home] ║ [:arrow_forward:][next]

<!-- navigation -->
[home]: ../README.md
[back]: ./email.md
[next]: ../README.md