document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit for the map to initialize
    setTimeout(setupMapSearchWhenAvailable, 1000);
});

// Look for map changes in Filament forms - for dynamic loading
document.addEventListener('wire:load', function() {
    setTimeout(setupMapSearchWhenAvailable, 500);
});

// Additional event handlers for Filament panel
document.addEventListener('turbo:load', function() {
    setTimeout(setupMapSearchWhenAvailable, 500);
});

document.addEventListener('turbo:frame-load', function() {
    setTimeout(setupMapSearchWhenAvailable, 500);
});

function setupMapSearchWhenAvailable() {
    // Check if the map element exists
    const mapElement = document.querySelector('.leaflet-container');
    
    if (!mapElement) {
        // If not found, try again after a short delay
        setTimeout(setupMapSearchWhenAvailable, 500);
        return;
    }
    
    // Check if we already added the search control (prevent duplicates)
    if (document.querySelector('.map-search-container')) {
        return;
    }
    
    // Create our search container
    const searchContainer = document.createElement('div');
    searchContainer.className = 'map-search-container';
    searchContainer.style.position = 'absolute';
    searchContainer.style.top = '10px';
    searchContainer.style.left = '50px';
    searchContainer.style.zIndex = '1000';
    searchContainer.style.width = '300px';
    
    // Create search input
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.placeholder = 'Cari lokasi...';
    searchInput.className = 'map-search-input';
    searchInput.style.width = '100%';
    searchInput.style.padding = '8px 12px';
    searchInput.style.border = '1px solid #ccc';
    searchInput.style.borderRadius = '4px';
    searchInput.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
    
    // Create results container
    const resultsContainer = document.createElement('div');
    resultsContainer.className = 'map-search-results';
    resultsContainer.style.display = 'none';
    resultsContainer.style.position = 'absolute';
    resultsContainer.style.width = '100%';
    resultsContainer.style.maxHeight = '200px';
    resultsContainer.style.overflowY = 'auto';
    resultsContainer.style.backgroundColor = 'white';
    resultsContainer.style.border = '1px solid #ccc';
    resultsContainer.style.borderRadius = '0 0 4px 4px';
    resultsContainer.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
    resultsContainer.style.marginTop = '2px';
    resultsContainer.style.zIndex = '1100';
    
    // Add elements to the DOM
    searchContainer.appendChild(searchInput);
    searchContainer.appendChild(resultsContainer);
    document.querySelector('.leaflet-container').appendChild(searchContainer);
    
    // Add event listeners
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        // Clear the previous timeout
        clearTimeout(searchTimeout);
        
        // Hide results if query is empty
        if (query === '') {
            resultsContainer.style.display = 'none';
            return;
        }
        
        // Set a timeout to prevent too many requests
        searchTimeout = setTimeout(function() {
            // Use Nominatim for geocoding (OpenStreetMap)
            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    resultsContainer.innerHTML = '';
                    
                    if (data.length === 0) {
                        resultsContainer.innerHTML = '<div style="padding: 8px 12px; color: #666;">Tidak ada hasil ditemukan</div>';
                        resultsContainer.style.display = 'block';
                        return;
                    }
                    
                    // Display results
                    data.slice(0, 5).forEach(result => {
                        const resultItem = document.createElement('div');
                        resultItem.className = 'map-search-result-item';
                        resultItem.style.padding = '8px 12px';
                        resultItem.style.borderBottom = '1px solid #eee';
                        resultItem.style.cursor = 'pointer';
                        resultItem.style.transition = 'background-color 0.2s';
                        resultItem.innerHTML = result.display_name;
                        
                        resultItem.addEventListener('mouseover', function() {
                            this.style.backgroundColor = '#f0f0f0';
                        });
                        
                        resultItem.addEventListener('mouseout', function() {
                            this.style.backgroundColor = 'white';
                        });
                        
                        resultItem.addEventListener('click', function() {
                            // Update the map view and marker position
                            updateMapLocation(result.lat, result.lon, result.display_name);
                            
                            // Clear the search and hide results
                            searchInput.value = result.display_name;
                            resultsContainer.style.display = 'none';
                        });
                        
                        resultsContainer.appendChild(resultItem);
                    });
                    
                    resultsContainer.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error searching for location:', error);
                    resultsContainer.innerHTML = '<div style="padding: 8px 12px; color: red;">Error searching for location</div>';
                    resultsContainer.style.display = 'block';
                });
        }, 500);
    });
    
    // Close results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchContainer.contains(e.target)) {
            resultsContainer.style.display = 'none';
        }
    });
}

function updateMapLocation(lat, lon, name) {
    // Access the Leaflet map instance
    let map;
    for (const key in window) {
        if (window[key] && window[key] instanceof L.Map) {
            map = window[key];
            break;
        }
    }
    
    if (!map) {
        // Try to get the map from the global scope
        if (typeof map !== 'undefined' && map instanceof L.Map) {
            // Use the global map variable
        } else {
            console.error('Could not find Leaflet map instance');
            return;
        }
    }
    
    // Update the map view
    map.setView([lat, lon], 15);
    
    // Find marker or create a new one
    let marker;
    map.eachLayer(function(layer) {
        if (layer instanceof L.Marker) {
            marker = layer;
        }
    });
    
    if (marker) {
        // Update existing marker position
        marker.setLatLng([lat, lon]);
    }
    
    // Update form fields for latitude and longitude
    const latInput = document.querySelector('input[name="latitude"], [name$="latitude"]');
    const lngInput = document.querySelector('input[name="longitude"], [name$="longitude"]');
    
    if (latInput) latInput.value = lat;
    if (lngInput) lngInput.value = lon;
    
    // Trigger change events to update any reactive data
    if (latInput) latInput.dispatchEvent(new Event('input', { bubbles: true }));
    if (lngInput) lngInput.dispatchEvent(new Event('input', { bubbles: true }));
    
    // Update the location field for FilamentPHP if it exists (for special map components)
    const locationInput = document.querySelector('[name$="location"]');
    if (locationInput) {
        try {
            const locationValue = JSON.stringify({ lat: parseFloat(lat), lng: parseFloat(lon) });
            locationInput.value = locationValue;
            locationInput.dispatchEvent(new Event('input', { bubbles: true }));
        } catch (e) {
            console.error('Error updating location input', e);
        }
    }
}