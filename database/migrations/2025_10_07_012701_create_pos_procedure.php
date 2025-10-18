<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        //Stored procedure untuk menambah item ke keranjang
        DB::unprepared("
             DROP PROCEDURE IF EXISTS sp_add_item;
    CREATE PROCEDURE sp_add_item(IN p_transaksi_id BIGINT, IN p_menu_id BIGINT, IN p_qty INT)
    BEGIN
        DECLARE v_harga INT;
        DECLARE v_sub INT;
        
        -- Ambil harga menu dan hitung subtotal
        SELECT harga INTO v_harga FROM menus WHERE id = p_menu_id AND aktif = 1;
        SET v_sub = v_harga * p_qty;
        
        -- Masukkan item baru ke tabel pesanan
        INSERT INTO pesanans (transaksi_id, menu_id, jumlah, harga_satuan, subtotal, created_at, updated_at)
        VALUES (p_transaksi_id, p_menu_id, p_qty, v_harga, v_sub, NOW(), NOW());
        
        -- PERBAIKAN UTAMA: Hitung ulang total di tabel transaksi berdasarkan SEMUA pesanan
        UPDATE transaksis t
        SET
            total = (SELECT SUM(subtotal) FROM pesanans WHERE transaksi_id = p_transaksi_id),
            updated_at = NOW()
        WHERE t.id = p_transaksi_id;
    END;
        ");

        // Stored procedure untuk proses pembayaran
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_bayar;
            CREATE PROCEDURE sp_bayar(IN p_transaksi_id BIGINT, IN p_bayar INT)
            BEGIN
                DECLARE v_meja BIGINT;
                -- Menggunakan 'bayar' yang merupakan nilai ENUM yang valid
                UPDATE transaksis SET dibayar = p_bayar, status = 'bayar', updated_at = NOW()
                WHERE id = p_transaksi_id;
                SELECT meja_id INTO v_meja FROM transaksis WHERE id = p_transaksi_id;
                UPDATE mejas SET status = 'kosong' WHERE id = v_meja;
            END;
        ");

        // Definisi sp_bayar yang salah (yang menggunakan 'dibayar') TELAH DIHAPUS.
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Hanya drop procedure yang dibuat di metode up() pada file ini
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_add_item");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_bayar");
        // Perintah DROP untuk sp_open_transaksi dipindahkan ke file migrasinya sendiri
    }
};
