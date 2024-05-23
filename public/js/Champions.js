document.addEventListener('DOMContentLoaded', () => {
    const apiKey = 'RGAPI-68f53cfa-f7bd-4e27-abe0-54851da20a34';
    const championsDiv = document.getElementById('champions');
    const championDetailsDiv = document.getElementById('champion-details');
    const searchInput = document.getElementById('search');

    fetch('https://ddragon.leagueoflegends.com/cdn/12.5.1/data/es_ES/champion.json')
        .then(response => response.json())
        .then(data => {
            const champions = data.data;
            for (let champ in champions) {
                const championContainer = document.createElement('div'); 
                championContainer.className = 'champion-container';
                const img = document.createElement('img');
                img.src = `https://ddragon.leagueoflegends.com/cdn/12.5.1/img/champion/${champions[champ].image.full}`;
                img.className = 'champion';
                img.alt = champions[champ].name;
                img.addEventListener('click', () => showChampionDetails(champions[champ]));
                championContainer.appendChild(img);
                const championName = document.createElement('p'); 
                championName.textContent = champions[champ].name;
                championName.style.maxWidth = '100px'; 
                championName.style.overflow = 'hidden'; 
                championName.style.textOverflow = 'ellipsis'; 
                championContainer.appendChild(championName); 
                championsDiv.appendChild(championContainer); 
            }
        });

    searchInput.addEventListener('input', (e) => {
        const searchText = e.target.value.toLowerCase();
        const championContainers = document.querySelectorAll('.champion-container');
        championContainers.forEach(container => {
            const championName = container.querySelector('p').textContent.toLowerCase(); 
            if (championName.includes(searchText)) {
                container.style.display = 'inline-block';
            } else {
                container.style.display = 'none';
            }
        });
    });

    function showChampionDetails(champion) {
        championDetailsDiv.innerHTML = `
            <h2>${champion.name}</h2>
            <p>${champion.title}</p>
            <img src="https://ddragon.leagueoflegends.com/cdn/img/champion/splash/${champion.id}_0.jpg" alt="${champion.name}" style="width:100%;">
            <div class="champion-detail">
                <strong>Rol:</strong> ${champion.tags.join(', ')}
            </div>
            <div class="champion-detail">
                <strong>Descripción:</strong> ${champion.blurb}
            </div>
        `;
    }
});
