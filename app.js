const search = document.querySelector('#search');
const rows = [...document.querySelectorAll('tbody tr')];
search.addEventListener('input', () => {
    let visible = 0;
    rows.forEach(row => {
        const match = (row.cells[0].textContent + ' ' + row.cells[1].textContent)
            .toLowerCase()
            .includes(search.value.toLowerCase());
        row.hidden = !match;
        if (match) visible++;
    });
    document.querySelector('#count').textContent = visible + ' barang';
});
document.querySelectorAll('.delete').forEach(form => form.addEventListener('submit', event => {
    if (!confirm('Yakin ingin menghapus barang ini?')) event.preventDefault();
}));
const stok = document.querySelector('#stok');
const harga = document.querySelector('#harga');
function preview() {
    document.querySelector('#preview').textContent = 'Estimasi nilai: ' + new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    }).format(Number(stok.value) * Number(harga.value));
}
stok.addEventListener('input', preview);
harga.addEventListener('input', preview);
preview();
const theme = document.querySelector('#theme');
function applyTheme(dark) {
    document.body.classList.toggle('dark', dark);
    theme.textContent = dark ? 'Mode terang' : 'Mode gelap';
}
applyTheme(localStorage.getItem('theme')==='dark');
theme.addEventListener('click', () => {
    const dark = !document.body.classList.contains('dark');
    localStorage.setItem('theme', dark ? 'dark' : 'light');
    applyTheme(dark);
});
