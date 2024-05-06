const fachTable = document.querySelector('#fach-table tbody');
const newDialog = document.querySelector('#new-fach');

fachTable.onclick = async e => {
  const cell = e.target.closest('td');
  if (!cell) return;
  const row = cell.parentElement.children;
  const form = new FormData(newDialog.children[0]);
  form.set('fach-id', row[0].innerHTML);
  form.set('fach-name', row[1].innerHTML);
  form.set('lb-id', row[2].innerHTML);
  form.set('action', 'update');

  newDialog.style.display = "block"

}