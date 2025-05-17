Get-ChildItem -Recurse | Where-Object { 
    ($_.FullName -like "*\app\Filament*" -or 
     $_.FullName -like "*\app\Models*" -or 
     $_.FullName -like "*\app\Policies*" -or 
     $_.FullName -like "*\config\filament.php" -or 
     $_.FullName -like "*\resources\views\filament*" -or 
     $_.FullName -like "*\database\migrations*") -and
    ($_.FullName -notlike "*\node_modules*" -and 
     $_.FullName -notlike "*\vendor*")
} | ForEach-Object { 
    if ($_.FullName -match "(app\\Filament|app\\Models|app\\Policies|config\\filament\.php|resources\\views\\filament|database\\migrations)") {
        $_.FullName
    }
}