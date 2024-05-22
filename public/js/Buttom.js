// public/js/search.js

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const regionSelect = document.getElementById('region-select');
    const resultsContainer = document.getElementById('results');

    searchInput.addEventListener('input', function () {
        const query = searchInput.value;
        const region = regionSelect.value;
        if (query.length > 2) {
            search(query, region);
        } else {
            resultsContainer.innerHTML = '';
        }
    });

    function search(query, region) {
        fetch(`/search?query=${query}&region=${region}`)
            .then(response => response.json())
            .then(data => {
                displayResults(data);
            })
            .catch(error => console.error('Error:', error));
    }

    function displayResults(data) {
        resultsContainer.innerHTML = '';
        if (data.champions && data.champions.length > 0) {
            const championsDiv = document.createElement('div');
            championsDiv.classList.add('results-section');
            championsDiv.innerHTML = '<h3>Champions</h3>';
            data.champions.forEach(champion => {
                const card = createCard(champion.name, champion.image.full);
                championsDiv.appendChild(card);
            });
            resultsContainer.appendChild(championsDiv);
        }
        if (data.summoners && data.summoners.length > 0) {
            const summonersDiv = document.createElement('div');
            summonersDiv.classList.add('results-section');
            summonersDiv.innerHTML = '<h3>Summoners</h3>';
            data.summoners.forEach(summoner => {
                const card = createCard(summoner.name, summoner.profileIconId);
                summonersDiv.appendChild(card);
            });
            resultsContainer.appendChild(summonersDiv);
        }
    }

    function createCard(name, imageUrl) {
        const card = document.createElement('div');
        card.classList.add('card');

        const image = document.createElement('img');
        image.src = `https://ddragon.leagueoflegends.com/cdn/img/champion/splash/${imageUrl}`;
        image.alt = name;

        const title = document.createElement('h4');
        title.textContent = name;

        card.appendChild(image);
        card.appendChild(title);

        return card;
    }
});
