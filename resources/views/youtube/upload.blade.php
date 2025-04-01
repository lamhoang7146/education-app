<form method="post" action="/youtube/upload" enctype="multipart/form-data">
    @csrf
    <p><input type="text" name="title" placeholder="Enter Video Title" /></p>
    <p><textarea name="summary" cols="30" rows="10" placeholder="Video description"></textarea></p>
    <p><input type="file" name="file" /></p>
    <input type="submit" name="submit" value="Submit" />
</form>
