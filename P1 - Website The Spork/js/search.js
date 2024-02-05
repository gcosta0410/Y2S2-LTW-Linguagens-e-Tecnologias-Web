const searchBar = document.querySelector('#searchQueryInput')
if (searchBar) {
    searchBar.addEventListener('input', async function() {
        const response = await fetch('../api/api_restaurants.php?search=' + this.value)
        const restaurants = await response.json()

        const ul = document.querySelector('.search-returnUL')
        ul.innerHTML = ''

        for (const restaurant of restaurants) {
            const img = document.createElement('img')
            img.src = "../" + restaurant.image.path;
            img.classList.add("img_result")

            const spanName = document.createElement('span')
            spanName.textContent = restaurant.name;

            const link = document.createElement('a')
            link.href = '/pages/restaurant.php?id=' + restaurant.restaurantID

            const li = document.createElement('li')
            li.classList.add("result")

            link.appendChild(img)
            link.appendChild(spanName)
            li.appendChild(link)
            ul.appendChild(li)
            ul.firstChild.style.setProperty('padding-top', "2em")
        }
    })
} 