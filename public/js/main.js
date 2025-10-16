// main.js - Archivo principal de JavaScript

console.log('✅ main.js cargado correctamente');

// Función para actualizar contador del carrito
function actualizarContadorCarrito() {
    fetch('?c=carrito&a=obtenerInfo')
        .then(response => response.json())
        .then(data => {
            const contadores = document.querySelectorAll('.cart-count');
            contadores.forEach(contador => {
                contador.textContent = data.total_items || 0;
            });
        })
        .catch(error => {
            console.log('Error actualizando contador:', error);
        });
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Aplicación inicializada');
    
    // Actualizar contador del carrito al cargar la página
    actualizarContadorCarrito();
});