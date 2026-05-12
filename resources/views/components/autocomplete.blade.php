{{--
  Autocomplete input component (Local filtering)
  Usage: @include('components.autocomplete', [
      'name' => 'jenis',
      'label' => 'Jenis *',
      'options' => $types->pluck('name'),
      'value' => old('jenis', $pet->jenis ?? ''),
      'placeholder' => 'Ketik untuk mencari...',
      'required' => true,
  ])
--}}
<div class="position-relative autocomplete-container" id="container-{{ $name }}">
  <label class="form-label">{{ $label }}</label>
  <input
    type="text"
    class="form-control @error($name) is-invalid @enderror"
    name="{{ $name }}"
    id="autocomplete-{{ $name }}"
    value="{{ $value ?? '' }}"
    placeholder="{{ $placeholder ?? 'Ketik untuk mencari...' }}"
    autocomplete="off"
    {{ !empty($required) ? 'required' : '' }}
  />
  @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
  
  <div class="autocomplete-dropdown border rounded shadow-sm bg-white position-absolute w-100" 
       id="dropdown-{{ $name }}" 
       style="display:none; z-index: 1050; max-height: 200px; overflow-y: auto; top: 100%;">
  </div>
</div>

<style>
  .autocomplete-item {
    padding: 8px 12px;
    cursor: pointer;
    transition: background 0.2s;
  }
  .autocomplete-item:hover {
    background-color: #f8f9fa;
    color: #696cff;
  }
  .autocomplete-item.active {
    background-color: #e7e7ff;
    color: #696cff;
  }
</style>

<script>
(function() {
  const input = document.getElementById('autocomplete-{{ $name }}');
  const dropdown = document.getElementById('dropdown-{{ $name }}');
  const options = {!! json_encode($options) !!};
  let currentFocus = -1;

  input.addEventListener('input', function() {
    const val = this.value;
    closeAllLists();
    if (!val) return false;
    
    currentFocus = -1;
    let matches = options.filter(opt => opt.toLowerCase().includes(val.toLowerCase()));
    
    if (matches.length > 0) {
      dropdown.innerHTML = '';
      matches.forEach(match => {
        const div = document.createElement('div');
        div.className = 'autocomplete-item';
        // Highlight matching part
        const index = match.toLowerCase().indexOf(val.toLowerCase());
        const before = match.substr(0, index);
        const middle = match.substr(index, val.length);
        const after = match.substr(index + val.length);
        div.innerHTML = before + "<strong>" + middle + "</strong>" + after;
        
        div.addEventListener('click', function() {
          input.value = match;
          closeAllLists();
        });
        dropdown.appendChild(div);
      });
      dropdown.style.display = 'block';
    } else {
      dropdown.innerHTML = '<div class="autocomplete-item text-muted small"><i class="bx bx-plus-circle me-1"></i>"' + val + '" akan ditambahkan</div>';
      dropdown.style.display = 'block';
    }
  });

  input.addEventListener('keydown', function(e) {
    let items = dropdown.getElementsByClassName('autocomplete-item');
    if (e.keyCode == 40) { // DOWN
      currentFocus++;
      addActive(items);
    } else if (e.keyCode == 38) { // UP
      currentFocus--;
      addActive(items);
    } else if (e.keyCode == 13) { // ENTER
      if (currentFocus > -1) {
        if (items[currentFocus]) items[currentFocus].click();
        e.preventDefault();
      }
    }
  });

  function addActive(items) {
    if (!items) return false;
    removeActive(items);
    if (currentFocus >= items.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (items.length - 1);
    items[currentFocus].classList.add('active');
    items[currentFocus].scrollIntoView({ block: 'nearest' });
  }

  function removeActive(items) {
    for (let i = 0; i < items.length; i++) {
      items[i].classList.remove('active');
    }
  }

  function closeAllLists() {
    dropdown.style.display = 'none';
  }

  document.addEventListener('click', function (e) {
    if (e.target !== input) closeAllLists();
  });
  
  input.addEventListener('focus', function() {
    if (this.value.length > 0) {
        this.dispatchEvent(new Event('input'));
    }
  });
})();
</script>
