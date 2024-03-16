<html>
    <head>

    </head>
    <body>
        <form method="POST" action="{{ route('admin.image-upload-s3') }}" enctype="multipart/form-data">
            @csrf
            <input  class="form-group" type="file" name="image" id="image_upload"/>
            <button type="submit">SUBMIT</button>
        </form>
    </body>
</html>
