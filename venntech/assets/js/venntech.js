
let VENN_MAX_WIDTH = 1024;
let VENN_MAX_HEIGHT = 768;

function timer_action_venntech(e, task_id, timer_id) {

    timer_id = typeof (timer_id) == 'undefined' ? '' : timer_id;

    $(e).addClass('disabled');

    var data = {};
    data.task_id = task_id;
    data.timer_id = timer_id;
    data.note = ''
    var reqUrl = admin_url + 'tasks/timer_tracking?single_task=' + false;
    $.post(reqUrl, data).done(function (response) {
        response = JSON.parse(response);
        window.location.reload();
    });
}

function resizeImageAndEnqueueFile(event, dropzone, origFile) {
    var origImg = new Image();
    origImg.src = event.target.result;
    origImg.addEventListener("load", function (event) {
        var width = event.target.width;
        var height = event.target.height;
        // Don't resize if it's small enough
        if (width <= VENN_MAX_WIDTH && height <= VENN_MAX_HEIGHT) {
            dropzone.enqueueFile(origFile);
            return;
        }
        // Calc new dims otherwise
        if (width > height) {
            VENN_MAX_WIDTH = 1024;
            VENN_MAX_HEIGHT = 768;
            if (width > VENN_MAX_WIDTH) {
                height *= VENN_MAX_WIDTH / width;
                width = VENN_MAX_WIDTH;
            }
        } else {
            VENN_MAX_WIDTH = 768;
            VENN_MAX_HEIGHT = 1024;
            if (height > VENN_MAX_HEIGHT) {
                width *= VENN_MAX_HEIGHT / height;
                height = VENN_MAX_HEIGHT;
            }
        }
        // Resize
        var canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        var ctx = canvas.getContext("2d");
        ctx.drawImage(origImg, 0, 0, width, height);
        var resizedFile = base64ToFile(canvas.toDataURL(), origFile);
        // Replace original with resized
        var origFileIndex = dropzone.files.indexOf(origFile);
        dropzone.files[origFileIndex] = resizedFile;
        // Enqueue added file manually making it available for
        // further processing by dropzone
        dropzone.enqueueFile(resizedFile);
    });
}

function base64ToFile(dataURI, origFile) {
    var byteString, mimestring;
    if (dataURI.split(',')[0].indexOf('base64') !== -1) {
        byteString = atob(dataURI.split(',')[1]);
    } else {
        byteString = decodeURI(dataURI.split(',')[1]);
    }
    mimestring = dataURI.split(',')[0].split(':')[1].split(';')[0];
    var content = new Array();
    for (var i = 0; i < byteString.length; i++) {
        content[i] = byteString.charCodeAt(i);
    }
    var newFile = new File(
        [new Uint8Array(content)], origFile.name, {type: mimestring}
    );
    // Copy props set by the dropzone in the original file
    var origProps = [
        "upload", "status", "previewElement", "previewTemplate", "accepted"
    ];
    $.each(origProps, function (i, p) {
        newFile[p] = origFile[p];
    });
    return newFile;
}