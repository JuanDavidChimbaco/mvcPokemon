<?php

namespace Modules\Admin\Controllers;

use Core\Controller;
use Core\Response;

class ReportController extends Controller
{
    public function index(): Response
    {
        $resumen = app()->database()->selectOne(
            "SELECT
                (SELECT COUNT(*) FROM productos) AS productos,
                (SELECT COUNT(*) FROM usuarios) AS usuarios,
                (SELECT COUNT(*) FROM pedidos WHERE estado <> 'cancelado') AS pedidos,
                (SELECT COALESCE(SUM(total), 0) FROM pedidos WHERE estado <> 'cancelado') AS ventas,
                (SELECT COUNT(*) FROM compras) AS compras,
                (SELECT COALESCE(SUM(total), 0) FROM compras WHERE estado = 'recibida') AS compras_recibidas"
        ) ?? [];

        $ventasMensuales = app()->database()->select(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS periodo, COUNT(*) AS pedidos,
                    COALESCE(SUM(total), 0) AS ventas
             FROM pedidos
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
               AND estado <> 'cancelado'
             GROUP BY DATE_FORMAT(created_at, '%Y-%m')
             ORDER BY periodo DESC"
        );

        return $this->view('admin/reports/index', compact('resumen', 'ventasMensuales'));
    }
}
