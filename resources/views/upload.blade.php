<form action="/upload" enctype="multipart/form-data" method="POST">
    @csrf

    <label for="excel_file">Файл Excel: <input type="file" name="excel_file" id="excel_file"></label><br>
    @error('excel_file')
        <small style="color:red">{{ $message }}</small>
    @enderror
    <br>
    <button type="submit">Отправить</button>
</form>
