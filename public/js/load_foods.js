class FoodList {
  constructor(containerId) {
    this.container = document.getElementById(containerId);
    this.step = 10;
    this.from = 0;
    this.to = this.step;
    this.lastSegment = this.getLastSegment();
    this.searchInput = document.getElementById('search_species_food');
    this.searchClear = document.getElementById('search_clear');
    this.loadMoreData = this.loadMoreData.bind(this);
    this.clearSearch = this.clearSearch.bind(this);
    this.foodItems = []; // Array to store rendered food items
    this.init();
  }

  async fetchData(from, to, searchQuery = '') {
    try {
      const response = await fetch(`/api/food_safety/${this.lastSegment}?from=${from}&to=${to}&q=${searchQuery}`);
      if (!response.ok) {
        throw new Error('Failed to fetch data');
      }
      const data = await response.json();
      return data;
    } catch (error) {
      console.error('Error fetching data:', error);
      throw error;
    }
  }

  getLastSegment() {
    const pathname = window.location.pathname;
    const segments = pathname.split('/');
    return segments[segments.length - 1];
  }

  renderFoodItems(data) {
    const fragment = document.createDocumentFragment();
  
    data.data.foods.forEach(food => {
      const listItem = document.createElement('li');
      listItem.innerHTML = `<div>${food.food}</div>
                            <div class="item" style="background: ${food.hex_color}">
                              <span class="mr-3">${food.safety_label}</span>
                              <img src="${food.filename}" height="40" alt="Icon">
                            </div>
                            <a href="#">Read more <i class="fa-solid fa-arrow-up-right-from-square"></i></a>`;
      fragment.appendChild(listItem);
      this.foodItems.push(listItem); // Add the new item to the array
    });
  
    // Check if the number of items returned is less than step or if the network response was 404
    if (data.data.foods.length < this.step || data.status === 404) {
      const endOfDataItem = document.createElement('li');
      endOfDataItem.innerHTML = '<div>End of data.</div>';
      fragment.appendChild(endOfDataItem);
    }
  
    // Clear previous content
    this.container.innerHTML = '';
  
    // Append the existing items from the array
    this.foodItems.forEach(item => {
      this.container.appendChild(item);
    });
  
    // Append the new items
    this.container.appendChild(fragment);
  }

  async loadMoreData() {
    // Increase 'from' and 'to' values by step
    this.from += this.step;
    this.to += this.step;
    try {
      const data = await this.fetchData(this.from, this.to, this.searchInput.value);
      this.renderFoodItems(data);
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  }

  clearSearch() {
    this.searchInput.value = ''; // Clear search input
    this.searchClear.style.visibility = 'hidden'; // Hide clear search button
    this.from = 0; // Reset 'from' value
    this.to = this.step; // Reset 'to' value
    this.foodItems = []; // Clear the array of rendered food items
    this.fetchData(this.from, this.to) // Fetch initial data after clearing search
      .then(data => this.renderFoodItems(data))
      .catch(error => console.error('Error fetching data:', error));
  }

  init() {
    // Event listeners for search input and clear search button
    this.searchInput.addEventListener('input', () => {
      if (this.searchInput.value) {
        this.searchClear.style.visibility = 'visible';
        this.from = 0; // Reset 'from' value
        this.to = this.step; // Reset 'to' value
        this.foodItems = []; // Clear the array of rendered food items
      } else {
        this.searchClear.style.visibility = 'hidden';
      }
      this.fetchData(this.from, this.to, this.searchInput.value)
        .then(data => this.renderFoodItems(data))
        .catch(error => console.error('Error fetching data:', error));
    });

    this.searchClear.addEventListener('click', this.clearSearch);

    // Initial load
    this.fetchData(this.from, this.to)
      .then(data => this.renderFoodItems(data))
      .catch(error => console.error('Error fetching data:', error));

    // Event listener for loading more data
    document.getElementById('load_more').addEventListener('click', this.loadMoreData);
  }
}

// Create FoodList instance
const foodList = new FoodList('food_list_container');