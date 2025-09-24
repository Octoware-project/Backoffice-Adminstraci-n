<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Factura;
use App\Models\Horas_Mensuales;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Facturas_y_Pagos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear facturas para el usuario con email user@test.com, solo con PDFs e imágenes PNG
        $email = 'user@test.com';
        
        // Definir los archivos de comprobantes (solo PDF)
        $facturas = [
            [
                'monto' => 1000.00,
                'tipo_pago' => 'Transferencia',
                'estado' => 'Pendiente',
                'mes' => 9, // Septiembre
                'archivo_origen' => 'comprobante1.pdf'
            ],
            [
                'monto' => 1100.00,
                'tipo_pago' => 'Efectivo',
                'estado' => 'Rechazado',
                'mes' => 8, // Agosto
                'archivo_origen' => 'comprobante2.pdf'
            ],
            [
                'monto' => 1200.00,
                'tipo_pago' => 'Débito',
                'estado' => 'Aceptado',
                'mes' => 7, // Julio
                'archivo_origen' => 'comprobante3.pdf'
            ]
        ];

        // Ruta del directorio de archivos de origen
        $sourceDir = database_path('seeders/files/comprobantes/');
        
        // Ruta del directorio de destino en la API Cooperativa
        $apiCooperativaStoragePath = base_path('../API_Cooperativa/storage/app/public/comprobantes/');
        
        // Crear directorio de destino si no existe
        if (!File::exists($apiCooperativaStoragePath)) {
            File::makeDirectory($apiCooperativaStoragePath, 0755, true);
        }

        foreach ($facturas as $index => $facturaData) {
            // Definir archivos origen y destino
            $archivoOrigen = $sourceDir . $facturaData['archivo_origen'];
            
            if (File::exists($archivoOrigen)) {
                // Generar nombre único para el archivo de destino
                $extension = pathinfo($facturaData['archivo_origen'], PATHINFO_EXTENSION);
                $nombreDestino = 'seeder_' . ($index + 1) . '_' . time() . '_' . uniqid() . '.' . $extension;
                $archivoDestino = $apiCooperativaStoragePath . $nombreDestino;
                
                // Copiar archivo al storage de la API Cooperativa
                if (File::copy($archivoOrigen, $archivoDestino)) {
                    // Crear factura en la base de datos
                    Factura::create([
                        'email' => $email,
                        'Monto' => $facturaData['monto'],
                        'Archivo_Comprobante' => 'comprobantes/' . $nombreDestino,
                        'Estado_Pago' => $facturaData['estado'],
                        'tipo_pago' => $facturaData['tipo_pago'],
                        'fecha_pago' => now()->year . '-' . str_pad($facturaData['mes'], 2, '0', STR_PAD_LEFT) . '-01',
                        'created_at' => now()->subMonths(10 - $facturaData['mes']),
                        'updated_at' => now()
                    ]);
                    
                    echo "✓ Factura " . ($index + 1) . " creada con comprobante: " . $nombreDestino . "\n";
                } else {
                    echo "✗ Error copiando archivo: " . $facturaData['archivo_origen'] . "\n";
                }
            } else {
                echo "✗ Archivo no encontrado: " . $archivoOrigen . "\n";
                
                // Crear factura sin comprobante como respaldo
                Factura::create([
                    'email' => $email,
                    'Monto' => $facturaData['monto'],
                    'Archivo_Comprobante' => null,
                    'Estado_Pago' => $facturaData['estado'],
                    'tipo_pago' => $facturaData['tipo_pago'],
                    'fecha_pago' => now()->year . '-' . str_pad($facturaData['mes'], 2, '0', STR_PAD_LEFT) . '-01',
                    'created_at' => now()->subMonths(10 - $facturaData['mes']),
                    'updated_at' => now()
                ]);
            }
        }
        
        // Crear imágenes PNG programáticamente si GD está disponible
        $this->createSamplePNGs($apiCooperativaStoragePath, $email);
        
        echo "\n🎉 Seeder de facturas completado exitosamente!\n";
    }
    
    /**
     * Crear múltiples imágenes PNG de prueba usando GD
     */
    private function createSamplePNGs($destinationPath, $email)
    {
        if (extension_loaded('gd')) {
            // Definir las imágenes PNG a crear
            $imagenesData = [
                [
                    'monto' => 1300.00,
                    'tipo_pago' => 'Transferencia',
                    'estado' => 'Pendiente',
                    'mes' => 6,
                    'bg_color' => [240, 248, 255], // Alice Blue
                    'text_color' => [25, 25, 112],  // Midnight Blue
                    'border_color' => [70, 130, 180] // Steel Blue
                ],
                [
                    'monto' => 1400.00,
                    'tipo_pago' => 'Crédito',
                    'estado' => 'Pendiente',
                    'mes' => 5,
                    'bg_color' => [240, 255, 240], // Honeydew
                    'text_color' => [0, 100, 0],    // Dark Green
                    'border_color' => [34, 139, 34] // Forest Green
                ],
                [
                    'monto' => 1500.00,
                    'tipo_pago' => 'Efectivo',
                    'estado' => 'Aceptado',
                    'mes' => 4,
                    'bg_color' => [255, 248, 220], // Cornsilk
                    'text_color' => [139, 69, 19],  // Saddle Brown
                    'border_color' => [210, 180, 140] // Tan
                ]
            ];

            foreach ($imagenesData as $index => $data) {
                $this->createPNGImage($destinationPath, $email, $data, $index + 4);
            }
        } else {
            echo "⚠ Extensión GD no disponible. Saltando creación de imágenes PNG.\n";
        }
    }
    
    /**
     * Crear una imagen PNG individual
     */
    private function createPNGImage($destinationPath, $email, $data, $numero)
    {
        // Crear imagen de 400x300 píxeles
        $image = imagecreate(400, 300);
        
        // Definir colores
        $background = imagecolorallocate($image, $data['bg_color'][0], $data['bg_color'][1], $data['bg_color'][2]);
        $textColor = imagecolorallocate($image, $data['text_color'][0], $data['text_color'][1], $data['text_color'][2]);
        $borderColor = imagecolorallocate($image, $data['border_color'][0], $data['border_color'][1], $data['border_color'][2]);
        
        // Crear bordes
        imagerectangle($image, 0, 0, 399, 299, $borderColor);
        imagerectangle($image, 3, 3, 396, 296, $borderColor);
        
        // Agregar texto
        imagestring($image, 5, 100, 40, "COMPROBANTE #$numero", $textColor);
        imagestring($image, 3, 120, 80, $data['tipo_pago'], $textColor);
        imagestring($image, 3, 120, 110, "Monto: $" . number_format($data['monto'], 2), $textColor);
        imagestring($image, 2, 120, 140, "Estado: " . $data['estado'], $textColor);
        imagestring($image, 2, 120, 160, "Fecha: " . date('d/m/Y'), $textColor);
        imagestring($image, 1, 100, 200, "Imagen generada automaticamente", $textColor);
        imagestring($image, 1, 110, 220, "para pruebas del sistema", $textColor);
        imagestring($image, 1, 130, 240, "Comprobante valido", $textColor);
        
        // Guardar imagen
        $nombreArchivo = "seeder_image_{$numero}_" . time() . "_" . uniqid() . ".png";
        $rutaCompleta = $destinationPath . $nombreArchivo;
        
        if (imagepng($image, $rutaCompleta)) {
            // Crear factura con esta imagen
            Factura::create([
                'email' => $email,
                'Monto' => $data['monto'],
                'Archivo_Comprobante' => 'comprobantes/' . $nombreArchivo,
                'Estado_Pago' => $data['estado'],
                'tipo_pago' => $data['tipo_pago'],
                'fecha_pago' => now()->year . '-' . str_pad($data['mes'], 2, '0', STR_PAD_LEFT) . '-01',
                'created_at' => now()->subMonths(10 - $data['mes']),
                'updated_at' => now()
            ]);
            
            echo "✓ Factura con imagen PNG #$numero creada: $nombreArchivo\n";
        } else {
            echo "✗ Error creando imagen PNG #$numero\n";
        }
        
        // Liberar memoria
        imagedestroy($image);
    }
}
