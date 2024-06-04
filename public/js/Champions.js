document.addEventListener('DOMContentLoaded', () => {
    const championsDiv = document.getElementById('champions');
    const searchInput = document.getElementById('search');

    const roleTranslation = {
        'Assassin': 'Asesino',
        'Fighter': 'Luchador',
        'Mage': 'Mago',
        'Marksman': 'Tirador',
        'Support': 'Apoyo',
        'Tank': 'Tanque'
    };

    fetch('https://ddragon.leagueoflegends.com/cdn/14.10.1/data/es_ES/champion.json')
        .then(response => response.json())
        .then(data => {
            const champions = Object.values(data.data);

            const rolesMap = {};

            champions.forEach(champ => {
                champ.tags.forEach(role => {
                    if (!rolesMap[role]) {
                        rolesMap[role] = [];
                    }
                    rolesMap[role].push(champ);
                });
            });

            for (const role in rolesMap) {
                const roleHeader = document.createElement('h2');
                roleHeader.className = 'header-container';
                roleHeader.textContent = roleTranslation[role] || role;
                championsDiv.appendChild(roleHeader);

                rolesMap[role].forEach(champ => {
                    const championContainer = document.createElement('div');
                    championContainer.className = 'champion-container';
                    championContainer.dataset.championId = champ.id;

                    const img = document.createElement('img');
                    img.src = `https://ddragon.leagueoflegends.com/cdn/14.10.1/img/champion/${champ.image.full}`;
                    img.className = 'champion';
                    img.alt = champ.name;
                    img.addEventListener('click', () => redirectToChampionPage(champ));

                    championContainer.appendChild(img);

                    const championName = document.createElement('p');
                    championName.textContent = champ.name;
                    championName.style.maxWidth = '60px';
                    championName.style.overflow = 'hidden';
                    championName.style.textOverflow = 'ellipsis';
                    championName.style.marginLeft = '1.875rem';

                    championContainer.appendChild(championName);
                    championsDiv.appendChild(championContainer);
                });
            }

            searchInput.addEventListener('input', (e) => {
                const searchText = e.target.value.toLowerCase();
                const roleHeaders = document.querySelectorAll('h2');
                const championContainers = document.querySelectorAll('.champion-container');

                const shownChampions = new Set();

                if (searchText) {
                    roleHeaders.forEach(header => header.style.display = 'none');
                    championContainers.forEach(container => {
                        const championName = container.querySelector('p').textContent.toLowerCase();
                        if (championName.includes(searchText)) {
                            if (!shownChampions.has(container.dataset.championId)) {
                                container.style.display = 'inline-block';
                                shownChampions.add(container.dataset.championId);
                            } else {
                                container.style.display = 'none';
                            }
                        } else {
                            container.style.display = 'none';
                        }
                    });
                } else {
                    roleHeaders.forEach(header => header.style.display = 'block');
                    championContainers.forEach(container => {
                        container.style.display = 'inline-block';
                    });
                }
            });
        })
        .catch(error => {
            console.error('Error fetching champion data:', error);
        });

    function redirectToChampionPage(champion) {
        window.location.href = `ChampionCompleto.php?champion=${champion.id}`;
    }
});
