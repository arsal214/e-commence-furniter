<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Courier proof of delivery — photos, signed receipts, a POD PDF.
     *
     * A table rather than a column on orders: a single delivery routinely comes
     * back with more than one artefact (doorstep photo plus signature slip), and
     * each one needs its own note and audit stamp.
     */
    public function up(): void
    {
        Schema::create('delivery_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            // Path on the *private* disk. These are internal records and must not
            // be reachable by guessing a URL under /storage.
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->string('mime', 100)->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_proofs');
    }
};
