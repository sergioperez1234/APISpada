<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('piezas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->required();
            $table->string('descripcion')->nullable();
            $table->decimal('precio', 8, 2);
            $table->string('tipo');
            $table->string('modelo');
            $table->string('imagen')->nullable(); // Ajustar esta columna para almacenar la ruta de la imagen
            $table->timestamps(); // Asegurar que las marcas de tiempo estén presentes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piezas');
    }
};
