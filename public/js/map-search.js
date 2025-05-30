document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit for the map to initialize
    setTimeout(setupMapSearchWhenAvailable, 1000);
});

// Look for map changes in Filament forms - for dynamic loading
document.addEventListener('livewire:load', function() {
    setTimeout(setupMapSearchWhenAvailable, 500);
});

// Additional event handlers for Filament panel
window.addEventListener('contentChanged', function() {
    setTimeout(setupMapSearchWhenAvailable, 500);
});

// Monitor DOM changes to detect when map is added dynamically
const observer = new MutationObserver(function(mutations) {
    for (const mutation of mutations) {
        if (mutation.addedNodes.length) {
            for (const node of mutation.addedNodes) {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    if (node.classList && node.classList.contains('leaflet-container') || 
                        node.querySelector('.leaflet-container')) {
                        setupMapSearchWhenAvailable();
                        break;
                    }
                }
            }
        }
    }
});

// Start observing the document body for changes
observer.observe(document.body, { childList: true, subtree: true });

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
                        
                        resultItem.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            // Log untuk debugging
                            console.log('Result clicked:', result);
                            
                            // Update the map view and marker position
                            updateMapLocation(result.lat, result.lon, result.display_name);
                            
                            // Clear the search and hide results
                            searchInput.value = result.display_name;
                            resultsContainer.style.display = 'none';
                            
                            // Fokus pada elemen lain untuk trigger perubahan
                            setTimeout(function() {
                                document.activeElement.blur();
                            }, 100);
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
    console.log('Updating map location:', lat, lon, name);
    
    // Access the Leaflet map instance
    let map;
    // Try to find map instance directly
    const containers = document.querySelectorAll('.leaflet-container');
    console.log('Found map containers:', containers.length);
    
    if (containers.length > 0) {
        // Approach 1: Check for _leaflet_id property in HTML element
        const container = containers[0];
        try {
            // Get all Leaflet maps from Leaflet's internal storage
            if (typeof L !== 'undefined' && L._maps) {
                // Find the map by container id
                for (const mapId in L._maps) {
                    const leafletMap = L._maps[mapId];
                    if (leafletMap._container === container) {
                        map = leafletMap;
                        console.log('Found map using L._maps');
                        break;
                    }
                }
            }
        } catch (e) {
            console.error('Error looking for map in L._maps:', e);
        }
        
        // Try to find Leaflet instance another way
        if (!map) {
            try {
                // Look for map in the global scope
                for (const key in window) {
                    if (window[key] && window[key] instanceof L.Map) {
                        map = window[key];
                        console.log('Found map in window scope');
                        break;
                    }
                }
                
                // Last attempt - get any map from Leaflet's registry
                if (!map && L._maps && Object.keys(L._maps).length > 0) {
                    map = L._maps[Object.keys(L._maps)[0]];
                    console.log('Using first available map from L._maps');
                }
            } catch (e) {
                console.error('Error searching for map:', e);
            }
        }
    }
    
    if (!map) {
        console.error('Could not find Leaflet map instance');
        return;
    }
    
    // Update the map view
    map.setView([lat, lon], 15);
    
    // Find marker or create a new one
    let marker;
    try {
        map.eachLayer(function(layer) {
            if (layer instanceof L.Marker) {
                marker = layer;
            }
        });
        
        if (marker) {
            // Update existing marker position
            marker.setLatLng([lat, lon]);
            console.log('Updated existing marker position');
        } else {
            // Create new marker if none exists
            marker = L.marker([lat, lon]).addTo(map);
            console.log('Created new marker');
        }
    } catch (e) {
        console.error('Error handling marker:', e);
    }
    
    console.log('Updating form fields with coordinates:', lat, lon);
    
    try {
        // Update form fields for latitude and longitude
        const latInput = document.querySelector('input[name="latitude"], [name$="latitude"]');
        const lngInput = document.querySelector('input[name="longitude"], [name$="longitude"]');
        
        console.log('Found input fields:', latInput, lngInput);
        
        if (latInput) {
            latInput.value = lat;
            latInput.dispatchEvent(new Event('input', { bubbles: true }));
            latInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
        
        if (lngInput) {
            lngInput.value = lon;
            lngInput.dispatchEvent(new Event('input', { bubbles: true }));
            lngInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
        
        // Update the location field for FilamentPHP if it exists (for special map components)
        const locationInputs = document.querySelectorAll('input[name$="location"], input[name*="location"]');
        console.log('Found location inputs:', locationInputs?.length);
        
        if (locationInputs && locationInputs.length > 0) {
            locationInputs.forEach(input => {
                try {
                    const locationValue = JSON.stringify({ lat: parseFloat(lat), lng: parseFloat(lon) });
                    console.log('Setting location value:', locationValue);
                    input.value = locationValue;
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                } catch (e) {
                    console.error('Error updating location input', e);
                }
            });
        }
        
        // Trigger a custom event that Filament might be listening for
        document.dispatchEvent(new CustomEvent('map-location-changed', {
            detail: { lat: parseFloat(lat), lng: parseFloat(lon) }
        }));
        
    } catch (e) {
        console.error('Error updating form fields:', e);
    }
}