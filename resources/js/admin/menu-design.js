/**
 * Menu Design Page JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles menu design customization, live preview, and export functionality
 */

class MenuDesignManager {
    constructor() {
        this.designSettings = {
            branding: {
                logo: null,
                restaurantName: 'Geez Restaurant',
                address: '123 Main Street, City, Country\nPhone: +1 234 567 8900\nEmail: info@geezrestaurant.com',
                description: 'Experience authentic flavors and exceptional dining in our warm, welcoming atmosphere. We pride ourselves on using fresh, locally-sourced ingredients to create memorable culinary experiences.',
                socialMedia: {
                    facebook: '',
                    instagram: '',
                    twitter: '',
                    website: ''
                }
            },
            layout: {
                template: 'modern',
                pageSize: 'a4',
                orientation: 'portrait',
                columns: 2,
                spacing: 'normal',
                sectionOrder: ['appetizers', 'main_courses', 'desserts', 'beverages'],
                enabledSections: {
                    appetizers: true,
                    main_courses: true,
                    desserts: true,
                    beverages: true
                }
            },
            colors: {
                preset: 'classic',
                primary: '#1f2937',
                background: '#ffffff',
                accent: '#dc2626',
                text: '#374151',
                backgroundType: 'solid'
            },
            typography: {
                fontFamily: 'Inter',
                titleSize: 32,
                headingSize: 24,
                bodySize: 16,
                priceSize: 18,
                boldHeadings: false,
                italicDescriptions: false,
                uppercaseCategories: false,
                lineHeight: 1.5,
                paragraphSpacing: 16
            },
            content: {
                showPrices: true,
                showDescriptions: true,
                showImages: false,
                showDietaryInfo: false,
                showSpiceLevel: false,
                priceFormat: 'currency_symbol',
                menuLanguage: 'en',
                footerContent: 'Thank you for dining with us! Please inform your server of any allergies or dietary requirements.',
                includeQrCode: false,
                qrUrl: ''
            }
        };
        
        this.currentZoom = '100%';
        this.menuItems = [];
        
        this.init();
    }

    /**
     * Initialize the menu design manager
     */
    init() {
        this.bindEvents();
        this.generateDummyMenuItems();
        this.loadDesignSettings();
        this.updatePreview();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Logo upload events
        this.bindLogoEvents();
        
        // Form input events
        this.bindFormEvents();
        
        // Layout events
        this.bindLayoutEvents();
        
        // Color events
        this.bindColorEvents();
        
        // Typography events
        this.bindTypographyEvents();
        
        // Content events
        this.bindContentEvents();
        
        // Preview events
        this.bindPreviewEvents();
        
        // Action button events
        this.bindActionEvents();
        
        // Modal events
        this.bindModalEvents();
    }

    /**
     * Bind logo upload events
     */
    bindLogoEvents() {
        const logoUpload = document.getElementById('logo-upload');
        const logoInput = document.getElementById('logo-input');
        const removeLogo = document.getElementById('remove-logo');

        if (logoUpload && logoInput) {
            logoUpload.addEventListener('click', () => logoInput.click());
            logoInput.addEventListener('change', (e) => this.handleLogoUpload(e));
        }

        if (removeLogo) {
            removeLogo.addEventListener('click', () => this.removeLogo());
        }
    }

    /**
     * Bind form input events
     */
    bindFormEvents() {
        // Restaurant information
        const restaurantName = document.getElementById('restaurant-name');
        const restaurantAddress = document.getElementById('restaurant-address');
        const restaurantDescription = document.getElementById('restaurant-description');

        if (restaurantName) {
            restaurantName.addEventListener('input', (e) => {
                this.designSettings.branding.restaurantName = e.target.value;
                this.updatePreview();
            });
        }

        if (restaurantAddress) {
            restaurantAddress.addEventListener('input', (e) => {
                this.designSettings.branding.address = e.target.value;
                this.updatePreview();
            });
        }

        if (restaurantDescription) {
            restaurantDescription.addEventListener('input', (e) => {
                this.designSettings.branding.description = e.target.value;
                this.updatePreview();
            });
        }

        // Social media links
        const socialInputs = ['facebook-url', 'instagram-url', 'twitter-url', 'website-url'];
        socialInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                const platform = inputId.replace('-url', '');
                input.addEventListener('input', (e) => {
                    this.designSettings.branding.socialMedia[platform] = e.target.value;
                    this.updatePreview();
                });
            }
        });

        // Footer content
        const footerContent = document.getElementById('footer-content');
        if (footerContent) {
            footerContent.addEventListener('input', (e) => {
                this.designSettings.content.footerContent = e.target.value;
                this.updatePreview();
            });
        }

        // QR URL
        const qrUrl = document.getElementById('qr-url');
        if (qrUrl) {
            qrUrl.addEventListener('input', (e) => {
                this.designSettings.content.qrUrl = e.target.value;
                this.updatePreview();
            });
        }
    }

    /**
     * Bind layout events
     */
    bindLayoutEvents() {
        // Layout templates
        document.querySelectorAll('.layout-option').forEach(option => {
            option.addEventListener('click', () => {
                document.querySelectorAll('.layout-option').forEach(o => o.classList.remove('active'));
                option.classList.add('active');
                this.designSettings.layout.template = option.dataset.layout;
                this.updatePreview();
            });
        });

        // Page settings
        const pageSettings = ['page-size', 'page-orientation', 'columns', 'spacing'];
        pageSettings.forEach(settingId => {
            const select = document.getElementById(settingId);
            if (select) {
                select.addEventListener('change', (e) => {
                    const setting = settingId.replace('-', '');
                    this.designSettings.layout[setting] = e.target.value;
                    this.updatePreview();
                });
            }
        });

        // Section toggles
        document.querySelectorAll('.section-toggle input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                const section = e.target.closest('.section-item').dataset.section;
                this.designSettings.layout.enabledSections[section] = e.target.checked;
                this.updatePreview();
            });
        });

        // Make section order sortable (simplified version)
        this.initSectionSorting();
    }

    /**
     * Bind color events
     */
    bindColorEvents() {
        // Color presets
        document.querySelectorAll('.color-preset').forEach(preset => {
            preset.addEventListener('click', () => {
                document.querySelectorAll('.color-preset').forEach(p => p.classList.remove('active'));
                preset.classList.add('active');
                this.applyColorPreset(preset.dataset.preset);
            });
        });

        // Custom color inputs
        const colorInputs = ['primary-color', 'background-color', 'accent-color', 'text-color'];
        colorInputs.forEach(inputId => {
            const colorInput = document.getElementById(inputId);
            const hexInput = colorInput?.parentNode.querySelector('.color-hex');
            
            if (colorInput && hexInput) {
                colorInput.addEventListener('input', (e) => {
                    const colorType = inputId.replace('-color', '');
                    this.designSettings.colors[colorType] = e.target.value;
                    hexInput.value = e.target.value.toUpperCase();
                    this.updatePreview();
                });
            }
        });

        // Background type
        document.querySelectorAll('input[name="background_type"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                this.designSettings.colors.backgroundType = e.target.value;
                this.updatePreview();
            });
        });
    }

    /**
     * Bind typography events
     */
    bindTypographyEvents() {
        // Font family
        const fontFamily = document.getElementById('font-family');
        if (fontFamily) {
            fontFamily.addEventListener('change', (e) => {
                this.designSettings.typography.fontFamily = e.target.value;
                this.updatePreview();
            });
        }

        // Font size sliders
        const sizeSliders = ['title-size', 'heading-size', 'body-size', 'price-size'];
        sizeSliders.forEach(sliderId => {
            const slider = document.getElementById(sliderId);
            const valueDisplay = slider?.parentNode.querySelector('.size-value');
            
            if (slider && valueDisplay) {
                slider.addEventListener('input', (e) => {
                    const sizeType = sliderId.replace('-size', '') + 'Size';
                    this.designSettings.typography[sizeType] = parseInt(e.target.value);
                    valueDisplay.textContent = `${e.target.value}px`;
                    this.updatePreview();
                });
            }
        });

        // Text styling checkboxes
        const styleCheckboxes = ['bold-headings', 'italic-descriptions', 'uppercase-categories'];
        styleCheckboxes.forEach(checkboxId => {
            const checkbox = document.getElementById(checkboxId);
            if (checkbox) {
                checkbox.addEventListener('change', (e) => {
                    const styleType = checkboxId.replace('-', '');
                    this.designSettings.typography[styleType] = e.target.checked;
                    this.updatePreview();
                });
            }
        });

        // Spacing sliders
        const spacingSliders = ['line-height', 'paragraph-spacing'];
        spacingSliders.forEach(sliderId => {
            const slider = document.getElementById(sliderId);
            const valueDisplay = slider?.parentNode.querySelector('.spacing-value');
            
            if (slider && valueDisplay) {
                slider.addEventListener('input', (e) => {
                    const spacingType = sliderId.replace('-', '');
                    this.designSettings.typography[spacingType] = parseFloat(e.target.value);
                    
                    if (sliderId === 'line-height') {
                        valueDisplay.textContent = e.target.value;
                    } else {
                        valueDisplay.textContent = `${e.target.value}px`;
                    }
                    
                    this.updatePreview();
                });
            }
        });
    }

    /**
     * Bind content events
     */
    bindContentEvents() {
        // Display options
        const displayOptions = ['show-prices', 'show-descriptions', 'show-images', 'show-dietary-info', 'show-spice-level'];
        displayOptions.forEach(optionId => {
            const checkbox = document.getElementById(optionId);
            if (checkbox) {
                checkbox.addEventListener('change', (e) => {
                    const optionType = optionId.replace('-', '');
                    this.designSettings.content[optionType] = e.target.checked;
                    this.updatePreview();
                });
            }
        });

        // Price format
        document.querySelectorAll('input[name="price_format"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                this.designSettings.content.priceFormat = e.target.value;
                this.updatePreview();
            });
        });

        // Menu language
        const menuLanguage = document.getElementById('menu-language');
        if (menuLanguage) {
            menuLanguage.addEventListener('change', (e) => {
                this.designSettings.content.menuLanguage = e.target.value;
                this.updatePreview();
            });
        }

        // QR Code
        const includeQrCode = document.getElementById('include-qr-code');
        const qrUrlInput = document.querySelector('.qr-url-input');
        
        if (includeQrCode && qrUrlInput) {
            includeQrCode.addEventListener('change', (e) => {
                this.designSettings.content.includeQrCode = e.target.checked;
                qrUrlInput.style.display = e.target.checked ? 'flex' : 'none';
                this.updatePreview();
            });
        }
    }

    /**
     * Bind preview events
     */
    bindPreviewEvents() {
        // Zoom controls
        document.querySelectorAll('.preview-zoom-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                document.querySelectorAll('.preview-zoom-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                this.currentZoom = btn.dataset.zoom;
                this.updatePreviewZoom();
            });
        });
    }

    /**
     * Bind action button events
     */
    bindActionEvents() {
        // Preview menu
        const previewBtn = document.querySelector('.preview-menu-btn');
        if (previewBtn) {
            previewBtn.addEventListener('click', () => this.openPreviewModal());
        }

        // Export design
        const exportBtn = document.querySelector('.export-design-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportDesign());
        }

        // Save design
        const saveBtn = document.querySelector('.save-design-btn');
        if (saveBtn) {
            saveBtn.addEventListener('click', () => this.saveDesign());
        }
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Preview modal
        this.bindModalCloseEvents('preview-modal', () => this.closePreviewModal());

        // Download PDF
        const downloadPdfBtn = document.querySelector('.download-pdf-btn');
        if (downloadPdfBtn) {
            downloadPdfBtn.addEventListener('click', () => this.downloadPdf());
        }

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closePreviewModal();
            }
        });
    }

    /**
     * Bind modal close events for a specific modal
     */
    bindModalCloseEvents(modalId, closeCallback) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const closeBtn = modal.querySelector('.modal-close');
        const cancelBtn = modal.querySelector('.close-preview-btn');
        const overlay = modal.querySelector('.modal-overlay');

        if (closeBtn) closeBtn.addEventListener('click', closeCallback);
        if (cancelBtn) cancelBtn.addEventListener('click', closeCallback);
        if (overlay) overlay.addEventListener('click', closeCallback);
    }

    /**
     * Handle logo upload
     */
    handleLogoUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Validate file type
        if (!file.type.startsWith('image/')) {
            this.showNotification('Please select a valid image file', 'error');
            return;
        }

        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            this.showNotification('Image file size must be less than 5MB', 'error');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            this.designSettings.branding.logo = e.target.result;
            this.showLogoPreview(e.target.result);
            this.updatePreview();
        };
        reader.readAsDataURL(file);
    }

    /**
     * Show logo preview
     */
    showLogoPreview(imageSrc) {
        const uploadArea = document.getElementById('logo-upload');
        const preview = document.getElementById('logo-preview');
        const logoImage = document.getElementById('logo-image');

        if (uploadArea && preview && logoImage) {
            uploadArea.style.display = 'none';
            preview.style.display = 'block';
            logoImage.src = imageSrc;
        }
    }

    /**
     * Remove logo
     */
    removeLogo() {
        this.designSettings.branding.logo = null;
        
        const uploadArea = document.getElementById('logo-upload');
        const preview = document.getElementById('logo-preview');
        const logoInput = document.getElementById('logo-input');

        if (uploadArea && preview && logoInput) {
            uploadArea.style.display = 'block';
            preview.style.display = 'none';
            logoInput.value = '';
        }

        this.updatePreview();
    }

    /**
     * Initialize section sorting
     */
    initSectionSorting() {
        // Simple drag and drop implementation
        const sectionList = document.getElementById('section-order');
        if (!sectionList) return;

        let draggedElement = null;

        sectionList.addEventListener('dragstart', (e) => {
            if (e.target.classList.contains('section-item')) {
                draggedElement = e.target;
                e.target.style.opacity = '0.5';
            }
        });

        sectionList.addEventListener('dragend', (e) => {
            if (e.target.classList.contains('section-item')) {
                e.target.style.opacity = '';
                draggedElement = null;
            }
        });

        sectionList.addEventListener('dragover', (e) => {
            e.preventDefault();
        });

        sectionList.addEventListener('drop', (e) => {
            e.preventDefault();
            if (draggedElement && e.target.classList.contains('section-item')) {
                const rect = e.target.getBoundingClientRect();
                const midpoint = rect.top + rect.height / 2;
                
                if (e.clientY < midpoint) {
                    sectionList.insertBefore(draggedElement, e.target);
                } else {
                    sectionList.insertBefore(draggedElement, e.target.nextSibling);
                }
                
                this.updateSectionOrder();
            }
        });

        // Make section items draggable
        document.querySelectorAll('.section-item').forEach(item => {
            item.draggable = true;
        });
    }

    /**
     * Update section order
     */
    updateSectionOrder() {
        const sections = Array.from(document.querySelectorAll('.section-item'));
        this.designSettings.layout.sectionOrder = sections.map(section => section.dataset.section);
        this.updatePreview();
    }

    /**
     * Apply color preset
     */
    applyColorPreset(preset) {
        const presets = {
            classic: {
                primary: '#1f2937',
                background: '#ffffff',
                accent: '#dc2626',
                text: '#374151'
            },
            elegant: {
                primary: '#374151',
                background: '#f3f4f6',
                accent: '#d97706',
                text: '#1f2937'
            },
            modern: {
                primary: '#111827',
                background: '#ffffff',
                accent: '#3b82f6',
                text: '#374151'
            },
            warm: {
                primary: '#451a03',
                background: '#fef7ed',
                accent: '#ea580c',
                text: '#7c2d12'
            }
        };

        if (presets[preset]) {
            Object.assign(this.designSettings.colors, presets[preset]);
            this.updateColorInputs();
            this.updatePreview();
        }
    }

    /**
     * Update color inputs
     */
    updateColorInputs() {
        const colorInputs = ['primary-color', 'background-color', 'accent-color', 'text-color'];
        colorInputs.forEach(inputId => {
            const colorInput = document.getElementById(inputId);
            const hexInput = colorInput?.parentNode.querySelector('.color-hex');
            const colorType = inputId.replace('-color', '');
            
            if (colorInput && hexInput && this.designSettings.colors[colorType]) {
                colorInput.value = this.designSettings.colors[colorType];
                hexInput.value = this.designSettings.colors[colorType].toUpperCase();
            }
        });
    }

    /**
     * Update preview zoom
     */
    updatePreviewZoom() {
        const menuPreview = document.querySelector('.menu-preview');
        if (menuPreview) {
            menuPreview.setAttribute('data-zoom', this.currentZoom);
        }
    }

    /**
     * Update preview
     */
    updatePreview() {
        const preview = document.getElementById('menu-preview');
        if (!preview) return;

        const menuHtml = this.generateMenuHtml();
        preview.innerHTML = menuHtml;
        this.applyPreviewStyles();
    }

    /**
     * Generate menu HTML
     */
    generateMenuHtml() {
        const settings = this.designSettings;
        
        let html = '<div class="preview-menu">';
        
        // Header
        html += '<div class="menu-header">';
        
        // Logo
        if (settings.branding.logo) {
            html += `<div class="menu-logo"><img src="${settings.branding.logo}" alt="Logo" style="max-width: 120px; max-height: 80px; object-fit: contain;"></div>`;
        } else {
            html += `<div class="menu-logo"><div class="logo-placeholder">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div></div>`;
        }
        
        // Restaurant name
        html += `<div class="menu-title">${settings.branding.restaurantName}</div>`;
        
        // Address
        if (settings.branding.address) {
            html += `<div class="menu-address">${settings.branding.address.replace(/\n/g, '<br>')}</div>`;
        }
        
        html += '</div>';
        
        // Content
        html += '<div class="menu-content">';
        
        // Generate sections based on order and enabled status
        settings.layout.sectionOrder.forEach(sectionKey => {
            if (settings.layout.enabledSections[sectionKey]) {
                const sectionItems = this.menuItems.filter(item => item.category === sectionKey);
                if (sectionItems.length > 0) {
                    html += this.generateSectionHtml(sectionKey, sectionItems);
                }
            }
        });
        
        html += '</div>';
        
        // Footer
        if (settings.content.footerContent) {
            html += `<div class="menu-footer"><p>${settings.content.footerContent}</p></div>`;
        }
        
        html += '</div>';
        
        return html;
    }

    /**
     * Generate section HTML
     */
    generateSectionHtml(sectionKey, items) {
        const sectionNames = {
            appetizers: 'Appetizers',
            main_courses: 'Main Courses',
            desserts: 'Desserts',
            beverages: 'Beverages'
        };
        
        let html = '<div class="menu-section">';
        html += `<h2 class="section-title">${sectionNames[sectionKey] || sectionKey}</h2>`;
        html += '<div class="menu-items">';
        
        items.forEach(item => {
            html += '<div class="menu-item">';
            html += '<div class="item-info">';
            html += `<h3 class="item-name">${item.name}</h3>`;
            
            if (this.designSettings.content.showDescriptions && item.description) {
                html += `<p class="item-description">${item.description}</p>`;
            }
            
            html += '</div>';
            
            if (this.designSettings.content.showPrices) {
                html += `<div class="item-price">${this.formatPrice(item.price)}</div>`;
            }
            
            html += '</div>';
        });
        
        html += '</div>';
        html += '</div>';
        
        return html;
    }

    /**
     * Format price based on settings
     */
    formatPrice(price) {
        const format = this.designSettings.content.priceFormat;
        
        switch (format) {
            case 'currency_symbol':
                return `£${price.toFixed(2)}`;
            case 'currency_code':
                return `GBP ${price.toFixed(2)}`;
            case 'no_currency':
                return price.toFixed(2);
            default:
                return `£${price.toFixed(2)}`;
        }
    }

    /**
     * Apply preview styles
     */
    applyPreviewStyles() {
        const previewMenu = document.querySelector('.preview-menu');
        if (!previewMenu) return;

        const settings = this.designSettings;
        
        // Apply typography
        previewMenu.style.fontFamily = `'${settings.typography.fontFamily}', sans-serif`;
        previewMenu.style.color = settings.colors.text;
        previewMenu.style.backgroundColor = settings.colors.background;
        previewMenu.style.lineHeight = settings.typography.lineHeight;
        
        // Apply title styles
        const menuTitle = previewMenu.querySelector('.menu-title');
        if (menuTitle) {
            menuTitle.style.fontSize = `${settings.typography.titleSize}px`;
            menuTitle.style.color = settings.colors.primary;
        }
        
        // Apply section title styles
        const sectionTitles = previewMenu.querySelectorAll('.section-title');
        sectionTitles.forEach(title => {
            title.style.fontSize = `${settings.typography.headingSize}px`;
            title.style.color = settings.colors.primary;
            title.style.fontWeight = settings.typography.boldHeadings ? 'bold' : '600';
            title.style.textTransform = settings.typography.uppercaseCategories ? 'uppercase' : 'none';
        });
        
        // Apply item name styles
        const itemNames = previewMenu.querySelectorAll('.item-name');
        itemNames.forEach(name => {
            name.style.fontSize = `${settings.typography.bodySize}px`;
            name.style.color = settings.colors.text;
        });
        
        // Apply description styles
        const descriptions = previewMenu.querySelectorAll('.item-description');
        descriptions.forEach(desc => {
            desc.style.fontSize = `${settings.typography.bodySize - 2}px`;
            desc.style.fontStyle = settings.typography.italicDescriptions ? 'italic' : 'normal';
            desc.style.marginBottom = `${settings.typography.paragraphSpacing}px`;
        });
        
        // Apply price styles
        const prices = previewMenu.querySelectorAll('.item-price');
        prices.forEach(price => {
            price.style.fontSize = `${settings.typography.priceSize}px`;
            price.style.color = settings.colors.accent;
        });
        
        // Apply layout styles
        const menuContent = previewMenu.querySelector('.menu-content');
        if (menuContent) {
            if (settings.layout.columns > 1) {
                menuContent.style.display = 'grid';
                menuContent.style.gridTemplateColumns = `repeat(${settings.layout.columns}, 1fr)`;
                menuContent.style.gap = '2rem';
            } else {
                menuContent.style.display = 'block';
            }
        }
    }

    /**
     * Open preview modal
     */
    openPreviewModal() {
        const modal = document.getElementById('preview-modal');
        const fullPreviewContainer = document.querySelector('.full-preview-container');
        
        if (modal && fullPreviewContainer) {
            // Generate full preview
            const menuHtml = this.generateMenuHtml();
            fullPreviewContainer.innerHTML = menuHtml;
            this.applyPreviewStyles();
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close preview modal
     */
    closePreviewModal() {
        const modal = document.getElementById('preview-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    /**
     * Export design
     */
    exportDesign() {
        const designData = {
            settings: this.designSettings,
            exported_at: new Date().toISOString(),
            version: '1.0'
        };
        
        const blob = new Blob([JSON.stringify(designData, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `menu-design-${new Date().toISOString().split('T')[0]}.json`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        this.showNotification('Design exported successfully', 'success');
    }

    /**
     * Save design
     */
    saveDesign() {
        // Simulate saving to backend
        this.showLoading();
        
        setTimeout(() => {
            this.hideLoading();
            this.showNotification('Design saved successfully', 'success');
        }, 1000);
    }

    /**
     * Download PDF
     */
    downloadPdf() {
        // This would integrate with a PDF generation library like jsPDF or Puppeteer
        this.showNotification('PDF download feature coming soon', 'info');
    }

    /**
     * Load design settings
     */
    loadDesignSettings() {
        // Populate form fields with current settings
        this.updateFormFields();
    }

    /**
     * Update form fields
     */
    updateFormFields() {
        const settings = this.designSettings;
        
        // Branding
        document.getElementById('restaurant-name').value = settings.branding.restaurantName;
        document.getElementById('restaurant-address').value = settings.branding.address;
        document.getElementById('restaurant-description').value = settings.branding.description;
        
        // Layout
        document.getElementById('page-size').value = settings.layout.pageSize;
        document.getElementById('page-orientation').value = settings.layout.orientation;
        document.getElementById('columns').value = settings.layout.columns;
        document.getElementById('spacing').value = settings.layout.spacing;
        
        // Colors
        this.updateColorInputs();
        
        // Typography
        document.getElementById('font-family').value = settings.typography.fontFamily;
        document.getElementById('title-size').value = settings.typography.titleSize;
        document.getElementById('heading-size').value = settings.typography.headingSize;
        document.getElementById('body-size').value = settings.typography.bodySize;
        document.getElementById('price-size').value = settings.typography.priceSize;
        
        // Content
        document.getElementById('show-prices').checked = settings.content.showPrices;
        document.getElementById('show-descriptions').checked = settings.content.showDescriptions;
        document.getElementById('show-images').checked = settings.content.showImages;
        document.getElementById('menu-language').value = settings.content.menuLanguage;
        document.getElementById('footer-content').value = settings.content.footerContent;
        
        // Update value displays
        this.updateValueDisplays();
    }

    /**
     * Update value displays
     */
    updateValueDisplays() {
        // Font size displays
        document.querySelector('#title-size + .size-value').textContent = `${this.designSettings.typography.titleSize}px`;
        document.querySelector('#heading-size + .size-value').textContent = `${this.designSettings.typography.headingSize}px`;
        document.querySelector('#body-size + .size-value').textContent = `${this.designSettings.typography.bodySize}px`;
        document.querySelector('#price-size + .size-value').textContent = `${this.designSettings.typography.priceSize}px`;
        
        // Spacing displays
        document.querySelector('#line-height + .spacing-value').textContent = this.designSettings.typography.lineHeight;
        document.querySelector('#paragraph-spacing + .spacing-value').textContent = `${this.designSettings.typography.paragraphSpacing}px`;
    }

    /**
     * Generate dummy menu items
     */
    generateDummyMenuItems() {
        this.menuItems = [
            // Appetizers
            { id: 1, name: 'Caesar Salad', description: 'Crisp romaine lettuce with parmesan, croutons, and caesar dressing', category: 'appetizers', price: 8.50 },
            { id: 2, name: 'Garlic Bread', description: 'Toasted bread with garlic butter and herbs', category: 'appetizers', price: 4.99 },
            { id: 3, name: 'Bruschetta', description: 'Grilled bread topped with fresh tomatoes, basil, and mozzarella', category: 'appetizers', price: 6.99 },
            
            // Main Courses
            { id: 4, name: 'Margherita Pizza', description: 'Classic pizza with tomato sauce, mozzarella, and fresh basil', category: 'main_courses', price: 12.99 },
            { id: 5, name: 'Grilled Salmon', description: 'Atlantic salmon grilled to perfection with lemon herb butter', category: 'main_courses', price: 18.99 },
            { id: 6, name: 'Beef Tenderloin', description: 'Premium beef tenderloin with roasted vegetables and red wine sauce', category: 'main_courses', price: 24.99 },
            { id: 7, name: 'Chicken Parmesan', description: 'Breaded chicken breast with marinara sauce and melted mozzarella', category: 'main_courses', price: 16.99 },
            
            // Desserts
            { id: 8, name: 'Chocolate Brownie', description: 'Rich chocolate brownie served warm with vanilla ice cream', category: 'desserts', price: 6.99 },
            { id: 9, name: 'Tiramisu', description: 'Classic Italian dessert with coffee-soaked ladyfingers', category: 'desserts', price: 7.99 },
            { id: 10, name: 'Cheesecake', description: 'New York style cheesecake with berry compote', category: 'desserts', price: 6.50 },
            
            // Beverages
            { id: 11, name: 'Fresh Orange Juice', description: 'Freshly squeezed orange juice served chilled', category: 'beverages', price: 3.99 },
            { id: 12, name: 'Cappuccino', description: 'Espresso with steamed milk and foam, dusted with cocoa', category: 'beverages', price: 3.50 },
            { id: 13, name: 'House Wine', description: 'Selection of red or white wine by the glass', category: 'beverages', price: 6.99 },
            { id: 14, name: 'Craft Beer', description: 'Local craft beer selection on tap', category: 'beverages', price: 4.99 }
        ];
    }

    /**
     * Utility methods
     */
    showLoading() {
        // Show loading indicator
    }

    hideLoading() {
        // Hide loading indicator
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
        
        // Manual close
        const closeBtn = notification.querySelector('.notification-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            });
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.menuDesignManager = new MenuDesignManager();
});
