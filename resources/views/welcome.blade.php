<!DOCTYPE html>
<html>
<head>
    <title>Validação de transações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">IMPORTAR TRANSAÇÕES</h1>
        <div class="row">
            <form method="POST" enctype="multipart/form-data" id="upload-file" action="{{ route('csv.upload') }}">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <input class="form-control" type="file" name="file" placeholder="Choose file" id="file">
                            <label for="file" class="form-label">Selecionar o arquivo para realizar upload</label>
                            @error('file')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary" id="submit">Importar</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row mt-3">
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
