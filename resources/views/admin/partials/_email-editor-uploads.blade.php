{{--
    Shared image-upload wiring for the two TinyMCE editors that write email
    bodies (campaign compose and email templates).

    Defines `window.emailEditorImageOptions` — spread it into a tinymce.init()
    call. Kept in one file because a mismatch between the two editors would mean
    images work in a saved template but break in a one-off campaign, or vice
    versa, which is a miserable thing to debug from a customer's inbox.
--}}
<script>
window.emailEditorImageOptions = {
    // Uploads the moment an image lands in the editor, so the body never holds
    // a base64 blob. Mail clients refuse to render `data:` images, and the
    // sanitiser strips them anyway.
    automatic_uploads: true,
    paste_data_images: true,
    images_reuse_filename: false,

    // TinyMCE rewrites same-host URLs to relative paths by default. A relative
    // src is meaningless in an inbox — the mail client has no site to resolve
    // it against — so every URL has to stay fully qualified.
    relative_urls: false,
    remove_script_host: false,
    convert_urls: false,

    images_upload_handler: function (blobInfo, progress) {
        return new Promise(function (resolve, reject) {
            var data = new FormData();
            data.append('file', blobInfo.blob(), blobInfo.filename());

            var xhr = new XMLHttpRequest();
            xhr.open('POST', @json(route('admin.email-images.store')));
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')?.content || '');
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.withCredentials = true;

            xhr.upload.onprogress = function (e) {
                if (e.lengthComputable) progress(e.loaded / e.total * 100);
            };

            xhr.onload = function () {
                var body = {};
                try { body = JSON.parse(xhr.responseText || '{}'); } catch (e) { /* non-JSON error page */ }

                if (xhr.status === 413) {
                    reject({ message: 'That image is too large for the server to accept.', remove: true });
                } else if (xhr.status === 419) {
                    reject({ message: 'Your session expired — reload the page and try again.', remove: true });
                } else if (xhr.status < 200 || xhr.status >= 300 || !body.location) {
                    reject({ message: body.message || ('Upload failed (' + xhr.status + ').'), remove: true });
                } else {
                    resolve(body.location);
                }
            };

            xhr.onerror = function () {
                reject({ message: 'Upload failed — the server could not be reached.', remove: true });
            };

            xhr.send(data);
        });
    },
};
</script>
