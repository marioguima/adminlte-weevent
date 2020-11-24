<div>
  <h1>Upload da foto</h1>

  <form action="#" method="post" wire:submit.prevent="storagePhoto">
    <label for="file-upload">clique aqui</label>
    <input type="file" id="file-upload" wire:model.defer="photo">
    <br />
    <button type="submit">Upload</button>
  </form>
</div>
