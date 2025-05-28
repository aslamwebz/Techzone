<div class="relative h-[80vh] min-h-[600px] w-full overflow-hidden">
    <!-- Slides -->
    <div class="relative h-full w-full" x-data="{
        activeSlide: 0,
        slides: [
            {
                title: 'Latest Tech Gadgets',
                subtitle: 'Discover our newest collection of cutting-edge technology',
                image: 'https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80',
                cta: 'Shop Now',
                url: '#featured',
                overlay: 'rgba(30, 41, 59, 0.5)'
            },
            {
                title: 'Premium Audio Experience',
                subtitle: 'Immerse yourself in crystal clear sound quality',
                image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80',
                cta: 'Explore Headphones',
                url: '#categories',
                overlay: 'rgba(30, 41, 59, 0.4)'
            },
            {
                title: 'Smart Home Solutions',
                subtitle: 'Upgrade your living space with smart technology',
                image: 'https://images.unsplash.com/photo-1558002038-1055907df827?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80',
                cta: 'View Smart Home',
                url: '#new-arrivals',
                overlay: 'rgba(30, 41, 59, 0.5)'
            }
        ],
        init() {
            // Auto-advance slides
            setInterval(() => {
                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
            }, 6000);
        },
        next() {
            this.activeSlide = (this.activeSlide + 1) % this.slides.length;
        },
        prev() {
            this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
        },
        goToSlide(index) {
            this.activeSlide = index;
        }
    }">
        <!-- Slides -->
        <div class="relative h-full w-full">
            <template x-for="(slide, index) in slides" :key="index">
                <div 
                    x-show="activeSlide === index"
                    x-transition:enter="transition ease-out duration-1000"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-1000"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute inset-0 w-full h-full"
                >
                    <!-- Background Image -->
                    <div 
                        class="absolute inset-0 bg-cover bg-center transform transition-all duration-1000" 
                        :style="`background-image: url('${slide.image}');`"
                        :class="{'scale-110': activeSlide === index}"
                    ></div>
                    
                    <!-- Overlay -->
                    <div class="absolute inset-0" :style="`background: ${slide.overlay};`"></div>
                    
                    <!-- Content -->
                    <div class="relative h-full flex items-center">
                        <div class="container mx-auto px-6 lg:px-8">
                            <div class="max-w-2xl text-white px-4 sm:px-6 lg:px-8">
                                <h1 
                                    class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 leading-tight"
                                    x-show="activeSlide === index"
                                    x-transition:enter="transition ease-out duration-500 delay-300"
                                    x-transition:enter-start="opacity-0 translate-y-8"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                >
                                    <span x-text="slide.title"></span>
                                </h1>
                                <p 
                                    class="text-lg md:text-xl mb-8 max-w-lg opacity-90"
                                    x-show="activeSlide === index"
                                    x-transition:enter="transition ease-out duration-500 delay-500"
                                    x-transition:enter-start="opacity-0 translate-y-8"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-text="slide.subtitle"
                                ></p>
                                <a 
                                    :href="slide.url"
                                    class="inline-block bg-white text-indigo-700 hover:bg-gray-100 px-8 py-3 rounded-full font-semibold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg"
                                    x-show="activeSlide === index"
                                    x-transition:enter="transition ease-out duration-500 delay-700"
                                    x-transition:enter-start="opacity-0 translate-y-8"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-text="slide.cta"
                                ></a>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            
            <!-- Navigation Arrows -->
            <button 
                @click="prev()"
                class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-3 rounded-full focus:outline-none transition-all duration-300 z-10"
                aria-label="Previous slide"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button 
                @click="next()"
                class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-3 rounded-full focus:outline-none transition-all duration-300 z-10"
                aria-label="Next slide"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            
            <!-- Slide Indicators -->
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex space-x-2 z-10">
                <template x-for="(slide, index) in slides" :key="index">
                    <button 
                        @click="goToSlide(index)"
                        class="w-3 h-3 rounded-full transition-all duration-300 focus:outline-none"
                        :class="activeSlide === index ? 'bg-white w-8' : 'bg-white/50 w-3'"
                        :aria-label="`Go to slide ${index + 1}`"
                    ></button>
                </template>
            </div>
        </div>
    </div>
</div>
