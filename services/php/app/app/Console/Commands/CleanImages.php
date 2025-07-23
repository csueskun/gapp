<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up images from the storage';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('Cleaning up ingrediente images...');
        $ingredientes = DB::table('ingrediente')->get();

        $oldFolder = public_path('images/ingrediente');
        $newFolder = public_path('images/_ingrediente');

        if (is_dir($oldFolder)) {
            if (!is_dir($newFolder)) {
                if (rename($oldFolder, $newFolder)) {
                    $this->info("Folder renamed to: $newFolder");
                    if (!mkdir($oldFolder, 0755, true)) {
                        $this->error("Failed to create folder: $oldFolder");
                        return;
                    } else {
                        $this->info("Folder created: $oldFolder");
                        rename($newFolder. DIRECTORY_SEPARATOR . "ingrediente.jpg", $oldFolder. DIRECTORY_SEPARATOR . 'ingrediente.jpg');
                    }
                } else {
                    $this->error("Failed to rename folder.");
                    return;
                }
            } else {
                $this->warn("Target folder already exists. Please delete first.: $newFolder");
                return;
            }
        } else {
            $this->error("Source folder does not exist: $oldFolder");
            return;
        }

        foreach ($ingredientes as $ingrediente) {
            $sourcePath = $newFolder. DIRECTORY_SEPARATOR . $ingrediente->imagen;
            $destinationPath = $oldFolder. DIRECTORY_SEPARATOR. $ingrediente->imagen;
            if (file_exists($sourcePath) && $ingrediente->imagen != "") {

                if (rename($sourcePath, $destinationPath)) {
                    $this->info("File moved: $destinationPath");
                } else {
                    $this->error("Failed to move file: $sourcePath");
                }
            } else {
                DB::table('ingrediente')->where('id', $ingrediente->id)->update(['imagen' => "ingrediente.jpg"]);
                $this->warn('File not found, ingredient updated: ' . $sourcePath);
            }
        }

        $this->info("Delete temp folder manually: $newFolder");

        $this->comment('Cleaning up producto images...');
        $productos = DB::table('producto')->get();

        $oldFolder = public_path('images/producto');
        $newFolder = public_path('images/_producto');

        if (is_dir($oldFolder)) {
            if (!is_dir($newFolder)) {
                if (rename($oldFolder, $newFolder)) {
                    $this->info("Folder renamed to: $newFolder");
                    if (!mkdir($oldFolder, 0755, true)) {
                        $this->error("Failed to create folder: $oldFolder");
                        return;
                    } else {
                        $this->info("Folder created: $oldFolder");
                        rename($newFolder. DIRECTORY_SEPARATOR . "producto.jpg", $oldFolder. DIRECTORY_SEPARATOR . 'producto.jpg');
                    }
                } else {
                    $this->error("Failed to rename folder.");
                    return;
                }
            } else {
                $this->warn("Target folder already exists. Please delete first.: $newFolder");
                return;
            }
        } else {
            $this->error("Source folder does not exist: $oldFolder");
            return;
        }

        foreach ($productos as $producto) {
            $sourcePath = $newFolder. DIRECTORY_SEPARATOR . $producto->imagen;
            $destinationPath = $oldFolder. DIRECTORY_SEPARATOR. $producto->imagen;
            if (file_exists($sourcePath) && $producto->imagen != "") {

                if (rename($sourcePath, $destinationPath)) {
                    $this->info("File moved: $destinationPath");
                } else {
                    $this->error("Failed to move file: $sourcePath");
                }
            } else {
                DB::table('producto')->where('id', $producto->id)->update(['imagen' => "producto.jpg"]);
                $this->warn('File not found, producto updated: ' . $sourcePath);
            }
        }

        $this->info("Delete temp folder manually: $newFolder");


        $this->comment('Cleaning up combo images...');
        $combos = DB::table('combo')->get();

        $oldFolder = public_path('images/combo');
        $newFolder = public_path('images/_combo');

        if (is_dir($oldFolder)) {
            if (!is_dir($newFolder)) {
                if (rename($oldFolder, $newFolder)) {
                    $this->info("Folder renamed to: $newFolder");
                    if (!mkdir($oldFolder, 0755, true)) {
                        $this->error("Failed to create folder: $oldFolder");
                        return;
                    } else {
                        $this->info("Folder created: $oldFolder");
                        rename($newFolder. DIRECTORY_SEPARATOR . "producto.jpg", $oldFolder. DIRECTORY_SEPARATOR . 'producto.jpg');
                    }
                } else {
                    $this->error("Failed to rename folder.");
                    return;
                }
            } else {
                $this->warn("Target folder already exists. Please delete first.: $newFolder");
                return;
            }
        } else {
            $this->error("Source folder does not exist: $oldFolder");
            return;
        }

        foreach ($combos as $combo) {
            $sourcePath = $newFolder. DIRECTORY_SEPARATOR . $combo->imagen;
            $destinationPath = $oldFolder. DIRECTORY_SEPARATOR. $combo->imagen;
            if (file_exists($sourcePath) && $combo->imagen != "") {

                if (rename($sourcePath, $destinationPath)) {
                    $this->info("File moved: $destinationPath");
                } else {
                    $this->error("Failed to move file: $sourcePath");
                }
            } else {
                DB::table('combo')->where('id', $combo->id)->update(['imagen' => "producto.jpg"]);
                $this->warn('File not found, combo updated: ' . $sourcePath);
            }
        }

        $this->info("Delete temp folder manually: $newFolder");
    }
}
