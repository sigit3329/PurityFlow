<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TDSData;

class TDSController extends Controller
{
    // Function to receive data from ESP32
    public function value($tdsValue, $getTotalGalonFromEEPROM)
    {
        // Save data to the database
        TDSData::create([
            'tds_value' => $tdsValue,
            'total_galon' => $getTotalGalonFromEEPROM,
            'quality' => $this->getTDSQuality($tdsValue)
        ]);

        // Send a success response
        return response()->json(['message' => 'Data stored successfully'], 200);
    }

    // Function to display data in view
    public function index()
    {
        // Get all records from t_d_s_data table
        $allTdsData = TDSData::orderBy('id', 'asc')->get();
        $latestTdsData = TDSData::orderBy('id', 'desc')->first();

        // Define water quality based on tdsValue range
        $tdsQuality = [];
        foreach ($allTdsData as $data) {
            $quality = $this->getTDSQuality($data->tds_value);
            $tdsQuality[] = $quality;
        }

        // Assuming $getTotalGalonFromEEPROM is provided from somewhere in your system
        $totalGalon = $latestTdsData ? $latestTdsData->total_galon : 0;

        // Data for the chart
        $labels = [];
        $chartData = [];
        foreach ($allTdsData as $data) {
            if ($data->created_at) {
                $labels[] = $data->created_at->format('Y-m-d');
            } else {
                $labels[] = 'No Date';
            }
            $chartData[] = $data->tds_value;
        }

        // Return view with the necessary data
        return view('tds.index', compact('allTdsData', 'latestTdsData', 'totalGalon', 'tdsQuality', 'labels', 'chartData'));
    }

    // Function to determine water quality based on TDS value
    private function getTDSQuality($tdsValue)
    {
        if ($tdsValue <= 150) {
            return 'Terbaik';
        } elseif ($tdsValue > 150 && $tdsValue <= 250) {
            return 'Baik';
        } elseif ($tdsValue > 250 && $tdsValue <= 300) {
            return 'Cukup';
        } elseif ($tdsValue > 300 && $tdsValue <= 500) {
            return 'Buruk';
        } else {
            return 'Warning';
        }
    }

    // Example function to get total galon from EEPROM or other sources
    // Assuming this function is not needed anymore
    // private function getTotalGalonFromEEPROM()
    // {
    //     // Implement the logic to get the total galon
    //     return 250; // Example value for total galon
    // }

    // Function to get data in JSON format
    public function get()
    {
        // Get all data from TDSData table
        $tdsData = TDSData::all();
    
        // Check if any data is found
        if (count($tdsData) > 0) {
            // Convert data to array for JSON response
            $data = $tdsData->toArray();
    
            // Send JSON response with the necessary data
            return response()->json(['tdsData' => $data], 200);
        } else {
            // If no data is found, send JSON response with a message
            return response()->json(['message' => 'No data found'], 404);
        }
    }
    
}
