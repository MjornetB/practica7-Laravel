<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Creació de la taula articles
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
        // Creació d'alguns articles assignats a l'usuari amb id 1
        DB::table('articles')->insert([
            ['id' => 1, 'title' => 'Descubrimiento de exoplanetas mediante inteligencia artificial.', 'user_id' => 1],
            ['id' => 2, 'title' => 'Los beneficios de la meditación en la salud mental.', 'user_id' => 1],
            ['id' => 3, 'title' => 'Cómo la realidad virtual está transformando la educación.', 'user_id' => 1],
            ['id' => 4, 'title' => 'La importancia de la diversidad en el lugar de trabajo.', 'user_id' => 1],
            ['id' => 5, 'title' => 'Avances en la edición genética para tratar enfermedades.', 'user_id' => 1],
            ['id' => 7, 'title' => 'Nuevas terapias para la enfermedad de Alzheimer.', 'user_id' => 1],
            ['id' => 9, 'title' => 'La neurociencia detrás de la toma de decisiones.', 'user_id' => 1],
            ['id' => 10, 'title' => 'La ciberseguridad en la era de la Internet de las cosas.', 'user_id' => 1],
            ['id' => 11, 'title' => 'La economía de las criptomonedas y el blockchain.', 'user_id' => 1],
            ['id' => 12, 'title' => 'Cómo la inteligencia artificial está revolucionando la atención médica.', 'user_id' => 1],
            ['id' => 13, 'title' => 'La conservación de la biodiversidad en peligro.', 'user_id' => 1],
            ['id' => 14, 'title' => 'El futuro de la movilidad eléctrica.', 'user_id' => 1],
            ['id' => 15, 'title' => 'El auge de la carne cultivada en laboratorio.', 'user_id' => 1],
            ['id' => 16, 'title' => 'Los desafíos éticos de la inteligencia artificial.', 'user_id' => 1],
            ['id' => 17, 'title' => 'La psicología de la felicidad y el bienestar.', 'user_id' => 1],
            ['id' => 18, 'title' => 'Innovaciones en la energía solar y eólica.', 'user_id' => 1],
            ['id' => 19, 'title' => 'La importancia de la educación STEM.', 'user_id' => 1],
            ['id' => 20, 'title' => 'Los efectos de la contaminación del aire en la salud.', 'user_id' => 1],
            ['id' => 21, 'title' => 'La conexión entre la dieta y la longevidad.', 'user_id' => 1],
            ['id' => 22, 'title' => 'La lucha contra la desinformación en línea.', 'user_id' => 1],
            ['id' => 23, 'title' => 'Nuevas terapias para el cáncer.', 'user_id' => 1],
            ['id' => 24, 'title' => 'La neuroplasticidad y la rehabilitación cerebral.', 'user_id' => 1],
            ['id' => 25, 'title' => 'Avances en la exploración espacial.', 'user_id' => 1],
            ['id' => 26, 'title' => 'La inteligencia artificial en la industria automotriz.', 'user_id' => 1],
            ['id' => 27, 'title' => 'La crisis global del agua y la sostenibilidad.', 'user_id' => 1],
            ['id' => 28, 'title' => 'El impacto de la inteligencia artificial en el arte.', 'user_id' => 1],
            ['id' => 29, 'title' => 'Los desafíos de la adopción de energía renovable.', 'user_id' => 1],
            ['id' => 30, 'title' => 'El microbioma intestinal y la salud digestiva.', 'user_id' => 1],
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
