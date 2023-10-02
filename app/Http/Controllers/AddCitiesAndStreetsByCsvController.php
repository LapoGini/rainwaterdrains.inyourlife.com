<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;
use App\Models\Street;


class AddCitiesAndStreetsByCsvController extends Controller
{
    public function index()
    {
        $clients = User::join('role_user', 'users.id', '=', 'role_user.user_id')->join('roles', 'role_user.role_id', '=', 'roles.id')->where('roles.id', 3)->select('users.*')->get();

        return view('pages.addStreetAndCities', compact('clients'));

    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'client' => 'required|exists:users,id',
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        if ($request->hasFile('csv_file')) {
            $path = $request->file('csv_file')->getRealPath();
            $csvContent = file_get_contents($path);

            // Converti il contenuto del CSV in un array di righe
            $rows = array_map('str_getcsv', explode("\n", $csvContent));

            // Estrai l'intestazione del CSV
            $header = array_shift($rows);

            if ($header !== ['comune', 'provincia', 'via']) {
                $formatError = back()->withErrors('Formato CSV non valido. L\'intestazione dovrebbe essere: comune, provincia, via');
                return $formatError;
            }

            $successfulCities = [];
            $successfulStreets = [];
            $failedStreets = [];

            foreach ($rows as $row) {
                if (count($row) != 3) {
                    
                    continue;
                }

                list($comune, $provincia, $via) = $row;

                $city = City::where([
                    ['name', '=', $comune],
                    ['district', '=', $provincia],
                    ['user_id', '=', $request->input('client')]
                ])->first();

                if ($city) {
                    $street = Street::where([
                        ['name', '=', $via],
                        ['city_id', '=', $city->id]
                    ])->first();
        
                    if (!$street) {
                        Street::create([
                            'name' => $via,
                            'city_id' => $city->id
                        ]);
                        $successfulStreets[] = $via;
                    } else {
                        $failedStreets[] = $via;
                    }
                } else {
                    $newCity = City::create([
                        'name' => $comune,
                        'district' => $provincia,
                        'pics' => 0,
                        'user_id' => $request->input('client')
                    ]);

                    if ($newCity) {
                        $successfulCities[] = $comune;
                        Street::create([
                            'name' => $via,
                            'city_id' => $newCity->id
                        ]);
                        $successfulStreets[] = $via;
                    }
                }
            }

            $message = "";
        
            if (count($successfulCities) > 0) {
                $message .= "Importazione completata.<br>Comuni inseriti: " . implode(', ', $successfulCities) . ".<br>Vie inserite: " . implode(', ', $successfulStreets) . ".";
            }
            
            if (count($failedStreets) > 0) {
                $message .= "<br>Vie non inserite  (già esistenti): " . implode(', ', $failedStreets) . ".";
            }
        
            $type = (count($failedStreets) > 0 ) ? 'error' : 'success';

            return back()->with(['message' => $message, 'type' => $type]);
        }
    }


}
