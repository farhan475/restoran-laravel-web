<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_open_transaksi;
            CREATE PROCEDURE sp_open_transaksi(IN p_meja_id BIGINT, IN p_waiter_id BIGINT, OUT p_transaksi_id BIGINT)
            BEGIN
                DECLARE v_exists INT DEFAULT 0;
                SELECT COUNT(*) INTO v_exists FROM transaksis WHERE meja_id = p_meja_id AND status = 'draft';
                IF v_exists > 0 THEN
                    SELECT id INTO p_transaksi_id FROM transaksis WHERE meja_id = p_meja_id AND status = 'draft' ORDER BY id DESC LIMIT 1;
                ELSE
                    INSERT INTO transaksis (meja_id, waiter_id, total, dibayar, status, created_at, updated_at)
                    VALUES (p_meja_id, p_waiter_id, 0, 0, 'draft', NOW(), NOW());
                    SET p_transaksi_id = LAST_INSERT_ID();
                    UPDATE mejas SET status = 'terpakai' WHERE id = p_meja_id;
                END IF;
            END;
        ");

        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_add_item;
            CREATE PROCEDURE sp_add_item(IN p_transaksi_id BIGINT, IN p_menu_id BIGINT, IN p_qty INT)
            BEGIN
                DECLARE v_harga INT;
                DECLARE v_sub INT;
                SELECT harga INTO v_harga FROM menus WHERE id = p_menu_id AND aktif = 1;
                SET v_sub = v_harga * p_qty;
                INSERT INTO pesanans (transaksi_id, menu_id, jumlah, harga_satuan, subtotal, created_at, updated_at)
                VALUES (p_transaksi_id, p_menu_id, p_qty, v_harga, v_sub, NOW(), NOW());
                UPDATE transaksis SET total = total + v_sub, updated_at = NOW() WHERE id = p_transaksi_id;
            END;
        ");

        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_bayar;
            CREATE PROCEDURE sp_bayar(IN p_transaksi_id BIGINT, IN p_bayar INT)
            BEGIN
                DECLARE v_meja BIGINT;
                UPDATE transaksis SET dibayar = p_bayar, status = 'dibayar', updated_at = NOW()
                WHERE id = p_transaksi_id;
                SELECT meja_id INTO v_meja FROM transaksis WHERE id = p_transaksi_id;
                UPDATE mejas SET status = 'kosong' WHERE id = v_meja;
            END;
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_open_transaksi");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_add_item");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_bayar");
    }
};
