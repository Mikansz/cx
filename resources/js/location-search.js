/**
 * Map Location Search for Office Locations
 */
document.addEventListener('DOMContentLoaded', function() {
    initLocationSearch();
});

function initLocationSearch() {
    // Wait for components to be fully loaded - might be lazy loaded in Filament
    const searchInput = document.getElementById('location-search-input');
    const mapContainer = document.getElementById('office-location-map');
    
    if (!searchInput || !mapContainer) {
        // Try again after a short delay
        setTimeout(initLocationSearch, 500);
        return;
    }
    
    // Create results container
    const resultsContainer = document.createElement('div');
    resultsContainer.id = 'search-results-container';
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
    resultsContainer.style.zIndex = '1001';
    
    // Insert results container after search input
    searchInput.parentNode.insertBefore(resultsContainer, searchInput.nextSibling);
    
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
                        resultItem.style.padding = '8px 12px';
                        resultItem.style.borderBottom = '1px solid #eee';
                        resultItem.style.cursor = 'pointer';
                        resultItem.style.backgroundColor = 'white';
                        resultItem.innerHTML = result.display_name;
                        
                        resultItem.addEventListener('mouseover', function() {
                            this.style.backgroundColor = '#f0f0f0';
                        });
                        
                        resultItem.addEventListener('mouseout', function() {
                            this.style.backgroundColor = 'white';
                        });
                        
                        resultItem.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            // Get latitude and longitude
                            const lat = parseFloat(result.lat);
                            const lng = parseFloat(result.lon);
                            
                            // Update the map and form fields
                            updateMapAndFields(lat, lng);
                            
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
        if (e.target !== searchInput && !resultsContainer.contains(e.target)) {
            resultsContainer.style.display = 'none';
        }
    });
    
    // Show a message that search is available
    console.log('Location search initialized');
}

function updateMapAndFields(lat, lng) {
    // Find the latitude and longitude input fields
    const latInput = document.querySelector('input[name="latitude"]');
    const lngInput = document.querySelector('input[name="longitude"]');
    
    if (latInput && lngInput) {
        // Update input values
        latInput.value = lat;
        lngInput.value = lng;
        
        // Trigger change events
        latInput.dispatchEvent(new Event('input', { bubbles: true }));
        latInput.dispatchEvent(new Event('change', { bubbles: true }));
        
        lngInput.dispatchEvent(new Event('input', { bubbles: true }));
        lngInput.dispatchEvent(new Event('change', { bubbles: true }));
    }
    
    // Find the location field for the map component
    const locationInput = document.querySelector('input[name="location"]');
    if (locationInput) {
        const locationValue = JSON.stringify({ lat: lat, lng: lng });
        locationInput.value = locationValue;
        locationInput.dispatchEvent(new Event('input', { bubbles: true }));
        locationInput.dispatchEvent(new Event('change', { bubbles: true }));
    }
    
    // Try to update map directly
    try {
        // Get the map container
        const mapContainer = document.getElementById('office-location-map');
        
        if (mapContainer) {
            // Find the Leaflet map instance
            let map;
            
            // Check if Leaflet is available
            if (typeof L !== 'undefined' && L._maps) {
                for (const key in L._maps) {
                    const m = L._maps[key];
                    if (m._container.contains(mapContainer)) {
                        map = m;
                        break;
                    }
                }
            }
            
            if (map) {
                // Update the map view
                map.setView([lat, lng], 15);
                
                // Find marker or create a new one
                let marker;
                map.eachLayer(function(layer) {
                    if (layer instanceof L.Marker) {
                        marker = layer;
                    }
                });
                
                if (marker) {
                    // Update existing marker position
                    marker.setLatLng([lat, lng]);
                }
            }
        }
    } catch (e) {
        console.error('Error updating map:', e);
    }
    
    console.log('Updated map location to:', lat, lng);
}