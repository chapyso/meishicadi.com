@props(['name', 'selectedColor' => 'blue', 'themeKey' => 'default'])

<div class="modern-color-picker" data-theme-key="{{ $themeKey }}">
    <!-- Hidden form field to store the selected color -->
    <input type="hidden" name="{{ $name }}" value="{{ $selectedColor }}" id="hidden-{{ $name }}">
    
    <div class="color-picker-header">
        <h6 class="picker-title">Choose Theme Color</h6>
    </div>
    
    <div class="color-selection-area">
        <!-- Color Wheel for Custom Colors -->
        <div class="color-wheel-section">
            <div class="color-wheel-trigger" title="Custom color picker">
                <div class="color-wheel">
                    <div class="wheel-inner"></div>
                </div>
                <span class="wheel-label">Custom</span>
            </div>
        </div>
        
        <!-- Preset Color Swatches -->
        <div class="preset-colors">
            @php
                $presetColors = [
                    'purple' => ['hex' => '#8B5CF6', 'name' => 'Purple'],
                    'indigo' => ['hex' => '#6366F1', 'name' => 'Indigo'], 
                    'blue' => ['hex' => '#3B82F6', 'name' => 'Blue'],
                    'teal' => ['hex' => '#14B8A6', 'name' => 'Teal'],
                    'green' => ['hex' => '#10B981', 'name' => 'Green'],
                    'yellow' => ['hex' => '#F59E0B', 'name' => 'Yellow'],
                    'orange' => ['hex' => '#F97316', 'name' => 'Orange'],
                    'red' => ['hex' => '#EF4444', 'name' => 'Red'],
                    'pink' => ['hex' => '#EC4899', 'name' => 'Pink'],
                    'gray' => ['hex' => '#6B7280', 'name' => 'Gray'],
                    'theme19' => ['hex' => '#907c6a', 'name' => 'Theme 19']
                ];
            @endphp
            @foreach($presetColors as $colorName => $colorData)
                <div class="preset-swatch {{ $selectedColor === $colorName ? 'selected' : '' }}" 
                     data-color="{{ $colorName }}" 
                     data-hex="{{ $colorData['hex'] }}"
                     style="background-color: {{ $colorData['hex'] }};"
                     title="{{ $colorData['name'] }}">
                    @if($selectedColor === $colorName)
                        <div class="selection-indicator">
                            <i class="fas fa-check"></i>
                        </div>
                    @endif
                    <input type="radio" name="{{ $name }}" value="{{ $colorName }}" 
                           {{ $selectedColor === $colorName ? 'checked' : '' }} style="display: none;">
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Advanced Color Picker (Hidden by default) -->
    <div class="advanced-color-picker" style="display: none;">
        <div class="picker-backdrop"></div>
        <div class="picker-container">
            <div class="picker-header">
                <h6 class="picker-title">Custom Color</h6>
                <button class="close-btn" id="closeAdvancedPicker">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="picker-content">
                <div class="color-wheel-large">
                    <canvas id="colorWheelCanvas" width="200" height="200"></canvas>
                    <div class="wheel-center"></div>
                </div>
                
                <div class="color-inputs">
                    <div class="input-group">
                        <label>Hex Color:</label>
                        <input type="text" id="hexInput" placeholder="#000000" maxlength="7">
                        <small class="input-hint">Click Apply to save your selection</small>
                    </div>
                </div>
                
                <div class="picker-actions">
                    <button class="btn btn-secondary" id="cancelBtn">Cancel</button>
                    <button class="btn btn-primary" id="applyBtn">
                        <i class="fas fa-check"></i> Apply Color
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.modern-color-picker {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    position: relative;
    min-width: 400px;
}

.color-picker-header {
    margin-bottom: 1.5rem;
}

.picker-title {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
}

.color-selection-area {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    margin-bottom: 1rem;
}

.color-wheel-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.color-wheel-trigger {
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    transition: transform 0.2s ease;
}

.color-wheel-trigger:hover {
    transform: scale(1.05);
}

.color-wheel {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    border: 3px solid #ffffff;
    background: conic-gradient(
        #ff0000 0deg, #ff8000 45deg, #ffff00 90deg, #80ff00 135deg, 
        #00ff00 180deg, #00ff80 225deg, #00ffff 270deg, #0080ff 315deg, #0000ff 360deg
    );
}

.wheel-inner {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ffffff;
    border: 2px solid #e5e7eb;
}

.wheel-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: #6b7280;
}

.preset-colors {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0.75rem;
    flex: 1;
}

.preset-swatch {
    position: relative;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    border: 3px solid #e5e7eb;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.preset-swatch:hover {
    border-color: #3b82f6;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.preset-swatch.selected {
    border-color: #3b82f6;
    border-width: 4px;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2), 0 4px 12px rgba(59, 130, 246, 0.3);
    transform: scale(1.05);
}

.selection-indicator {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}

.selection-indicator i {
    color: #ffffff;
    font-size: 1rem;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6);
    font-weight: 600;
}

/* Advanced Color Picker Styles */
.advanced-color-picker {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.picker-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(2px);
}

.advanced-color-picker .picker-container {
    position: relative;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    min-width: 320px;
    max-width: 90vw;
    max-height: 90vh;
    overflow-y: auto;
    z-index: 10000;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.picker-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #6b7280;
    cursor: pointer;
    padding: 0;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.close-btn:hover {
    background-color: #f3f4f6;
    color: #374151;
}

.picker-content {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}

.color-wheel-large {
    position: relative;
    display: flex;
    justify-content: center;
    margin-bottom: 1rem;
}

.color-wheel-large canvas {
    border-radius: 50%;
    border: 3px solid #e5e7eb;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.wheel-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ffffff;
    border: 2px solid #e5e7eb;
    pointer-events: none;
}

.color-inputs {
    padding: 1rem;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.input-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.input-group label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
}

.input-group input {
    padding: 0.75rem;
    border: 2px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, monospace;
    background: #ffffff;
    color: #374151;
    font-weight: 500;
    transition: all 0.2s ease;
}

.input-group input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.input-hint {
    font-size: 0.75rem;
    color: #6b7280;
    margin-top: 0.25rem;
    font-style: italic;
}

.picker-actions {
    display: flex;
    gap: 0.875rem;
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.btn {
    flex: 1;
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    border: 2px solid transparent;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #ffffff;
    border-color: #3b82f6;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border-color: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}

.btn-secondary {
    background: #ffffff;
    color: #374151;
    border-color: #d1d5db;
}

.btn-secondary:hover {
    background: #f9fafb;
    border-color: #9ca3af;
    transform: translateY(-1px);
}

/* Responsive Design */
@media (max-width: 640px) {
    .modern-color-picker {
        min-width: 320px;
        padding: 1rem;
    }
    
    .color-selection-area {
        flex-direction: column;
        gap: 1rem;
    }
    
    .preset-colors {
        grid-template-columns: repeat(5, 1fr);
        gap: 0.5rem;
    }
    
    .preset-swatch {
        width: 36px;
        height: 36px;
    }
    
    .advanced-color-picker .picker-container {
        min-width: 300px;
        max-width: 95vw;
        margin: 1rem;
    }
    
    .color-wheel {
        width: 44px;
        height: 44px;
    }
}
</style>

<script>
class ColorPicker {
    constructor(container) {
        this.container = container;
        this.currentColor = '#3B82F6';
        this.isDragging = false;
        this.fieldName = this.container.querySelector('input[type="hidden"]').name;
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
    }
    
    setupEventListeners() {
        // Preset color selection
        const presetSwatches = this.container.querySelectorAll('.preset-swatch');
        presetSwatches.forEach(swatch => {
            swatch.addEventListener('click', (e) => {
                e.stopPropagation();
                this.selectPresetColor(swatch);
            });
        });
        
        // Custom color trigger
        const customTrigger = this.container.querySelector('.color-wheel-trigger');
        if (customTrigger) {
            customTrigger.addEventListener('click', (e) => {
                e.stopPropagation();
                this.openAdvancedPicker();
            });
        }
        
        // Advanced picker events
        const closeBtn = this.container.querySelector('#closeAdvancedPicker');
        const cancelBtn = this.container.querySelector('#cancelBtn');
        const applyBtn = this.container.querySelector('#applyBtn');
        const backdrop = this.container.querySelector('.picker-backdrop');
        
        if (closeBtn) closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.closeAdvancedPicker();
        });
        
        if (cancelBtn) cancelBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.closeAdvancedPicker();
        });
        
        if (applyBtn) applyBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.applyAdvancedColor();
        });
        
        // Click outside to close
        if (backdrop) backdrop.addEventListener('click', (e) => {
            e.stopPropagation();
            this.closeAdvancedPicker();
        });
        
        // Prevent closing when clicking inside the picker container
        const pickerContainer = this.container.querySelector('.picker-container');
        if (pickerContainer) {
            pickerContainer.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }
    }
    
    selectPresetColor(swatch) {
        // Remove existing selection
        const existingSelected = this.container.querySelector('.preset-swatch.selected');
        if (existingSelected) {
            existingSelected.classList.remove('selected');
            const existingIndicator = existingSelected.querySelector('.selection-indicator');
            if (existingIndicator) existingIndicator.remove();
        }
        
        // Add selection to clicked swatch
        swatch.classList.add('selected');
        const indicator = document.createElement('div');
        indicator.className = 'selection-indicator';
        indicator.innerHTML = '<i class="fas fa-check"></i>';
        swatch.appendChild(indicator);
        
        // Update radio button
        const radio = swatch.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
        
        // Get color data
        const colorName = swatch.dataset.color;
        const hexValue = swatch.dataset.hex;
        
        // Update current color
        this.currentColor = hexValue;
        
        // Update hidden form field
        this.updateFormField(colorName);
        
        // Close advanced picker if it's open
        this.closeAdvancedPicker();
        
        // Dispatch custom event
        this.container.dispatchEvent(new CustomEvent('colorSelected', {
            detail: { color: colorName, hex: hexValue }
        }));
    }
    
    updateFormField(colorName) {
        // Update hidden input field
        const hiddenInput = this.container.querySelector(`input[name="${this.fieldName}"]`);
        if (hiddenInput) {
            hiddenInput.value = colorName;
        }
        
        // Also update any radio buttons with the same name
        const radioButtons = document.querySelectorAll(`input[name="${this.fieldName}"]`);
        radioButtons.forEach(radio => {
            radio.checked = (radio.value === colorName);
        });
        
        console.log('Form field updated:', this.fieldName, '=', colorName);
    }
    
    openAdvancedPicker() {
        const advancedPicker = this.container.querySelector('.advanced-color-picker');
        if (advancedPicker) {
            advancedPicker.style.display = 'block';
            this.initializeColorWheel();
            this.setupInputEvents();
        }
    }
    
    closeAdvancedPicker() {
        const advancedPicker = this.container.querySelector('.advanced-color-picker');
        if (advancedPicker) {
            advancedPicker.style.display = 'none';
        }
    }
    
    initializeColorWheel() {
        const canvas = this.container.querySelector('#colorWheelCanvas');
        if (!canvas) return;
        
        this.drawColorWheel(canvas);
        this.setupCanvasEvents(canvas);
    }
    
    drawColorWheel(canvas) {
        const ctx = canvas.getContext('2d');
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = Math.min(centerX, centerY) - 10;
        
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        for (let angle = 0; angle < 360; angle++) {
            for (let saturation = 0; saturation <= radius; saturation++) {
                const x = centerX + saturation * Math.cos(angle * Math.PI / 180);
                const y = centerY + saturation * Math.sin(angle * Math.PI / 180);
                
                const hue = angle;
                const sat = (saturation / radius) * 100;
                const lightness = 50;
                
                ctx.fillStyle = `hsl(${hue}, ${sat}%, ${lightness}%)`;
                ctx.fillRect(x, y, 1, 1);
            }
        }
    }
    
    setupCanvasEvents(canvas) {
        // Add touch events for mobile support
        canvas.addEventListener('touchstart', (e) => {
            e.preventDefault();
            const touch = e.touches[0];
            this.handleWheelClick({ clientX: touch.clientX, clientY: touch.clientY }, canvas);
        });
        
        // Mouse events
        canvas.addEventListener('mousedown', (e) => {
            this.isDragging = true;
            this.handleWheelClick(e, canvas);
        });
        
        document.addEventListener('mousemove', (e) => {
            if (this.isDragging) {
                this.handleWheelClick(e, canvas);
            }
        });
        
        document.addEventListener('mouseup', () => {
            this.isDragging = false;
        });
    }
    
    handleWheelClick(e, canvas) {
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = Math.min(centerX, centerY) - 10;
        
        const deltaX = x - centerX;
        const deltaY = y - centerY;
        const distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY);
        
        if (distance <= radius) {
            const angle = Math.atan2(deltaY, deltaX) * 180 / Math.PI;
            const hue = (angle + 360) % 360;
            const saturation = Math.min(100, (distance / radius) * 100);
            
            this.updateColorFromHSL(hue, saturation, 50);
        }
    }
    
    updateColorFromHSL(h, s, l) {
        const hex = this.hslToHex(h, s, l);
        this.currentColor = hex;
        
        // Update hex input
        const hexInput = this.container.querySelector('#hexInput');
        if (hexInput) hexInput.value = hex;
    }
    
    hslToHex(h, s, l) {
        s /= 100;
        l /= 100;
        
        const c = (1 - Math.abs(2 * l - 1)) * s;
        const x = c * (1 - Math.abs((h / 60) % 2 - 1));
        const m = l - c / 2;
        
        let r, g, b;
        
        if (h >= 0 && h < 60) {
            [r, g, b] = [c, x, 0];
        } else if (h >= 60 && h < 120) {
            [r, g, b] = [x, c, 0];
        } else if (h >= 120 && h < 180) {
            [r, g, b] = [0, c, x];
        } else if (h >= 180 && h < 240) {
            [r, g, b] = [0, x, c];
        } else if (h >= 240 && h < 300) {
            [r, g, b] = [x, 0, c];
        } else {
            [r, g, b] = [c, 0, x];
        }
        
        const rHex = Math.round((r + m) * 255).toString(16).padStart(2, '0');
        const gHex = Math.round((g + m) * 255).toString(16).padStart(2, '0');
        const bHex = Math.round((b + m) * 255).toString(16).padStart(2, '0');
        
        return `#${rHex}${gHex}${bHex}`;
    }
    
    applyAdvancedColor() {
        const hexInput = this.container.querySelector('#hexInput');
        if (hexInput && this.isValidHex(hexInput.value)) {
            this.currentColor = hexInput.value;
            
            // Update form field with custom color
            this.updateFormField('custom');
            
            // Dispatch custom event
            this.container.dispatchEvent(new CustomEvent('colorSelected', {
                detail: { color: 'custom', hex: this.currentColor }
            }));
        }
        
        this.closeAdvancedPicker();
    }
    
    setupInputEvents() {
        const hexInput = this.container.querySelector('#hexInput');
        if (hexInput) {
            // Prevent closing when clicking on input
            hexInput.addEventListener('click', (e) => {
                e.stopPropagation();
            });
            
            // Prevent closing when focusing on input
            hexInput.addEventListener('focus', (e) => {
                e.stopPropagation();
            });
            
            // Handle Enter key to apply color
            hexInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.applyAdvancedColor();
                }
            });
        }
    }
    
    isValidHex(hex) {
        return /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(hex);
    }
}

// Initialize color picker when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const colorPickers = document.querySelectorAll('.modern-color-picker');
    colorPickers.forEach(picker => {
        new ColorPicker(picker);
    });
});
</script> 