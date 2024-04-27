// Función para eliminar una fila de la tabla de listas
function eliminarLista(button, id_lista) {
    let fila = button.parentNode.parentNode;
    let table = document.getElementById("table"); 
    table.deleteRow(row.rowIndex);
}

// Función para redirigir a la vista de ver/modificar lista
function verModificarLista(button, id_lista) {
    // Redirigir a otra página pasando el nombre de la lista como parámetro
    window.location.href = "ver_lista.php?id=" + id_lista;
}
