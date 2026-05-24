<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Producto enviado a WooCommerce</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f5f5f5; margin:0; padding:24px; color:#222;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; margin:0 auto; background:#fff; border-radius:8px; overflow:hidden;">
        <tr>
            <td style="background:#1a4d8c; color:#fff; padding:20px 24px;">
                <h2 style="margin:0; font-size:18px;">Clandent — Producto {{ $action === 'updated' ? 'actualizado' : 'creado' }} en WooCommerce</h2>
            </td>
        </tr>
        <tr>
            <td style="padding:24px;">
                <p style="margin:0 0 16px;">Se ha {{ $action === 'updated' ? 'actualizado' : 'enviado' }} un producto a la tienda WooCommerce.</p>

                <table cellpadding="6" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:14px;">
                    <tr><td style="border-bottom:1px solid #eee;"><strong>Producto</strong></td><td style="border-bottom:1px solid #eee;">{{ $product->name }}</td></tr>
                    <tr><td style="border-bottom:1px solid #eee;"><strong>SKU</strong></td><td style="border-bottom:1px solid #eee;">{{ $product->sku }}</td></tr>
                    @if($product->brand)
                    <tr><td style="border-bottom:1px solid #eee;"><strong>Marca</strong></td><td style="border-bottom:1px solid #eee;">{{ $product->brand }}</td></tr>
                    @endif
                    <tr><td style="border-bottom:1px solid #eee;"><strong>Precio</strong></td><td style="border-bottom:1px solid #eee;">${{ number_format((float) $product->regular_price, 0, ',', '.') }}</td></tr>
                    @if($product->sale_price)
                    <tr><td style="border-bottom:1px solid #eee;"><strong>Precio oferta</strong></td><td style="border-bottom:1px solid #eee;">${{ number_format((float) $product->sale_price, 0, ',', '.') }}</td></tr>
                    @endif
                    <tr><td style="border-bottom:1px solid #eee;"><strong>WC Product ID</strong></td><td style="border-bottom:1px solid #eee;">{{ $wcProductId ?? $product->wc_product_id ?? 'N/A' }}</td></tr>
                    @if($userName)
                    <tr><td style="border-bottom:1px solid #eee;"><strong>Enviado por</strong></td><td style="border-bottom:1px solid #eee;">{{ $userName }}</td></tr>
                    @endif
                    <tr><td><strong>Fecha</strong></td><td>{{ now()->format('d/m/Y H:i') }}</td></tr>
                </table>

                @if($wcStoreUrl && ($wcProductId ?? $product->wc_product_id))
                <p style="margin:24px 0 0;">
                    <a href="{{ rtrim($wcStoreUrl, '/') }}/wp-admin/post.php?post={{ $wcProductId ?? $product->wc_product_id }}&action=edit"
                       style="display:inline-block; background:#1a4d8c; color:#fff; padding:10px 18px; border-radius:4px; text-decoration:none; font-size:14px;">
                        Ver en WooCommerce
                    </a>
                </p>
                @endif
            </td>
        </tr>
        <tr>
            <td style="background:#fafafa; padding:14px 24px; font-size:12px; color:#888; text-align:center;">
                Notificación automática — Sistema Clandent Productos
            </td>
        </tr>
    </table>
</body>
</html>
