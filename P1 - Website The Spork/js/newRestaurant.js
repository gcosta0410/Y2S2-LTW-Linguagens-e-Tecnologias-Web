let nr = document.querySelectorAll("tr").length;

function newMenuItem(){
    const tablebody = document.getElementById("menuTable").querySelector("tbody")
    const row = tablebody.insertRow(-1);
    const rmbutton = row.insertCell(0);
    rmbutton.innerHTML='<button onclick="remMenuItem(this)" class="minus" type="button" ><i class="fas fa-minus"></i></button>';
    const name = row.insertCell(1);
    name.innerHTML='<input type="text" required name="dish[]" placeholder="Product name">';
    const price = row.insertCell(2);
    price.innerHTML='<input type="number" step="0.01" required name="price[]" placeholder="Price">€';
    const type = row.insertCell(3);
    type.innerHTML='<select name="type[]">\n' +
        '                        <option>comida</option>\n' +
        '                        <option>bebida</option>\n' +
        '                    </select>';
    const category = row.insertCell(4);
    category.innerHTML='<input type="text" required name="categories2[]" placeholder="Categories, separated by a space">';
    const allergens = row.insertCell(5);
    allergens.innerHTML='<input type="text" name="allergens[]" placeholder="Allergens, separated by a space">';
    const image = row.insertCell(6);
    image.classList.add("img-input");
    image.innerHTML='<i class="fas fa-image"></i><input name="item-img[]" class="item-img" required type="file" accept=".jpg, .jpeg, .png">';
    nr++;
}

/**
 *
 * @param {Element} button
 */
function remMenuItem(button){
    if(nr > 1){
        const tr = button.parentElement.parentElement
        tr.parentNode.removeChild(tr);
        nr--;
    }
}

