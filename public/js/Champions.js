document.addEventListener('DOMContentLoaded', () => {
    const championsDiv = document.getElementById('champions');
    const searchInput = document.getElementById('search');

    fetch('https://ddragon.leagueoflegends.com/cdn/14.10.1/data/es_ES/champion.json')
        .then(response => response.json())
        .then(data => {
            const champions = data.data;
            for (let champ in champions) {
                const championContainer = document.createElement('div'); 
                championContainer.className = 'champion-container';
                
                const img = document.createElement('img');
                img.src = `https://ddragon.leagueoflegends.com/cdn/14.10.1/img/champion/${champions[champ].image.full}`;
                img.className = 'champion';
                img.alt = champions[champ].name;
                img.addEventListener('click', () => redirectToChampionPage(champions[champ]));
                
                championContainer.appendChild(img);
                
                const championName = document.createElement('p'); 
                championName.textContent = champions[champ].name;
                championName.style.maxWidth = '60px';  
                championName.style.overflow = 'hidden'; 
                championName.style.textOverflow = 'ellipsis'; 
                championName.style.marginLeft = '1.875rem'; 
                
                championContainer.appendChild(championName); 
                championsDiv.appendChild(championContainer); 
            }
        })
        .catch(error => {
            console.error('Error fetching champion data:', error);
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

    function redirectToChampionPage(champion) {
        window.location.href = `ChampionCompleto.php?champion=${champion.id}`;
    }
});