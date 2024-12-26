<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Schema\Blueprint;
use App\Models\User;

class DatabaseController extends Controller
{
    public function createDatabase(Request $request)
    {
        $user = $request->user();
        $dbName = $user->db_name;

        try {
            DB::statement("CREATE DATABASE `$dbName`");

            $this->createUserTables($dbName, $user);

            // return redirect()->back()->with('success', 'Database berhasil dibuat.');
            return response()->json(['success' => 'Database berhasil dibuat.', 'dbName' => $dbName]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error on'.$e]);
        }
    }

    protected function createUserTables($dbName, $user)
    {
        config(['database.connections.user' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $dbName,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
        ]]);

        DB::purge('user');
        $connection = DB::connection('user');

        $connection->getSchemaBuilder()->create('info', function (Blueprint $table) {
            $table->id();
            $table->string('id_user');
            $table->string('dbName');
            $table->DATETIME('end_time');
            $table->timestamps();
        });

        $connection->table('info')->insert([
            'id_user' => $user->id,
            'dbName' => $dbName,
            'end_time' => now()->addDays(3),
        ]);

        $connection->getSchemaBuilder()->create('framework', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link')->nullable();
            $table->string('script')->nullable();
            $table->timestamps();
        });
        
        $connection->table('framework')->insert([
            'name' => 'bootstrap',
            'link' => '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">',
            'script' => '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>'
        ]);

        $connection->getSchemaBuilder()->create('stylesheet', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link')->nullable();
            $table->string('script')->nullable();
            $table->timestamps();
        });
        $connection->table('stylesheet')->insert([
            [
                'name' => 'awesome',
                'link' => '<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">',
            ],
            [
                'name' => 'iom-icon',
                'link' => '<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>',
            ]
        ]);


        $connection->getSchemaBuilder()->create('library', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link')->nullable();
            $table->string('script')->nullable();
            $table->timestamps();
        });
        $connection->table('library')->insert([
            'name' => 'jquery',
            'script' => '<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>',
        ]);

        DB::setDefaultConnection('mysql');
    }

    public function getInfoDB(Request $request) {
        $dbName = $request->user()->db_name;
        config(['database.connections.user' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $dbName,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
        ]]);

        DB::purge('user');
        $connection = DB::connection('user');

        try {
            $data = $connection->table('info')->first();
            $namaUser = User::where('db_name', $dbName)->first();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungkan ke database: ' . $e->getMessage(),
            ], 500);
        }

        $tableCount = $connection->table('information_schema.tables')
                                ->where('table_schema', $dbName)
                                ->count();
        $storageInfo = $connection->table('information_schema.tables')
                                ->selectRaw('SUM(data_length + index_length) AS total_storage')
                                ->where('table_schema', $dbName)
                                ->first();
                                
        DB::setDefaultConnection('mysql');
        return response()->json(['infoData' => $data, 'tableCount'=> $tableCount, 'storageInfo' => $storageInfo, 'namaUser'=> $namaUser]);
    }
}

