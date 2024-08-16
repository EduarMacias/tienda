function showSection(section) {
    document.getElementById('sectionAgregarProducto').style.display = 'none';
    document.getElementById('sectionAgregarCategoria').style.display = 'none';
    document.getElementById('sectionAgregarTalla').style.display = 'none';
    document.getElementById('sectionAgregarColor').style.display = 'none';
    document.getElementById('sectionPedidos').style.display = 'none';
    document.getElementById('sectionVerProductos').style.display = 'none';
    document.getElementById(section).style.display = 'block';
}
