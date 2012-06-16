<?php
echo $errors;
echo form_open_multipart('/backdoor/uploadGPSData');
?>

<label style="color: brown;"for="userfile">GPS .log File:</label>
<input type="file" name="userfile" size="50" />
<br />
<input type="submit" name="submit" value="Upload File" />

</form>
