$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
$publicDirectory = Join-Path $projectRoot 'public'
$temporaryDirectory = Join-Path $projectRoot 'storage\app\tmp\uploads'
$router = Join-Path $projectRoot 'vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php'
$php = (Get-Command php -ErrorAction Stop).Source

New-Item -ItemType Directory -Force -Path $temporaryDirectory | Out-Null

Push-Location $publicDirectory

try {
    & $php `
        '-d' "upload_tmp_dir=$temporaryDirectory" `
        '-d' "sys_temp_dir=$temporaryDirectory" `
        '-S' '127.0.0.1:8000' `
        $router

    exit $LASTEXITCODE
} finally {
    Pop-Location
}
