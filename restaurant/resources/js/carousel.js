export default class Carousel {
    /**
     * @callback MoveCallback
     * @param {number} index
     */

    /**
     * crée une div avec la classe carousel__container et place les éléments du carousel dans cette div et place la div dans le carousel passé en paramètre
     * place un event listener sur la fenetre pour gérer le resize et un event sur les fleches du clavier pour gérer le défilement du carousel
     * @param {HTMLElement} element
     * @param {Object} options
     * @param {Object} [options.slidesToScroll = 1] Nombre d'éléments à faire défiler
     * @param {Object} [options.slidesVisible = 1] Nombre d'éléments visible dans un slide
     * @param {Object} [options.loop = false]  Doit-on boucler en fin de carousel
     * @param {Object} [options.leftIcon = undefined]  path vers l'icone de la flèche de gauche
     * @param {Object} [options.rightIcon = undefined]  path vers l'icone de la flèche de droite
     */
    constructor(element, options = {}) {
        this.element = element;
        this.options = Object.assign(
          {},
          {
            slidesToScroll: 1,
            slidesVisible: 1,
          },
          options
        );
        let children = [].slice.call(element.children);
        this.isMobile = false;
        this.currentItem = 0;
        this.moveCallbacks = [];
    
        // Modification du DOM
        this.container = this.createDivWithClass("carousel__container");
        this.element.setAttribute("tabindex", "0");
        this.element.appendChild(this.container);
        this.items = children.map((child) => {
          child.classList.add("carousel__item");
          this.container.appendChild(child);
          return child;
        });
        this.setStyle();
        this.createNavigation();
    
        // Evenements
        this.moveCallbacks.forEach((cb) => cb(0));
        this.onWindowResize();
        window.addEventListener("resize", this.onWindowResize.bind(this));
        this.element.addEventListener("keyup", (e) => {
          if (e.key === "ArrowRight" || e.key === "Right") {
            this.next();
          } else if (e.key === "ArrowLeft" || e.key === "Left") {
            this.prev();
          }
        });
      }

    /**
     * @param {string} className
     * @returns {HTMLElement}
     */
    createDivWithClass(className) {
        let div = document.createElement("div");
        div.setAttribute("class", className);
        return div;
    }

    /**
     * gere la taille des éléments du carousel en fonction du nombre d'éléments visible et du nombre d'éléments à faire défiler
     */
    setStyle() {
        let ratio = this.items.length / this.slidesVisible;
        this.container.style.width = ratio * 100 + "%";
        this.items.forEach(
            (item) =>
                (item.style.width = 100 / this.slidesVisible / ratio + "%")
        );
    }

    /**
     * créee les flèches de navigation et place un callback dans $this.moveCallbacks pour gérer l'affichage des flèches
     */
    createNavigation() {
        let nextButton = this.createDivWithClass("carousel__next");
        let prevButton = this.createDivWithClass("carousel__prev");
        if(this.options.leftIcon !== undefined && this.options.rightIcon !== undefined){
            console.log(this.options);
            nextButton.innerHTML = `<img src="${this.options.rightIcon}" alt="next">`;
            prevButton.innerHTML = `<img src="${this.options.leftIcon}" alt="prev">`;
        }
        this.element.appendChild(nextButton);
        this.element.appendChild(prevButton);
        nextButton.addEventListener("click", this.next.bind(this));
        prevButton.addEventListener("click", this.prev.bind(this));
        if (this.options.loop === true) {
            return;
        }
        this.onMove((index) => {
            if (index === 0) {
                prevButton.classList.add("carousel__prev--hidden");
            } else {
                prevButton.classList.remove("carousel__prev--hidden");
            }
            if (
                this.items[this.currentItem + this.slidesVisible] === undefined
            ) {
                nextButton.classList.add("carousel__next--hidden");
            } else {
                nextButton.classList.remove("carousel__next--hidden");
            }
        });
    }


    /**
     * @param {MoveCallback} cb
     */
    onMove(cb) {
        this.moveCallbacks.push(cb);
    }
    
    /**
     * déplace le carousel vers les élément suivant
     */
    next() {
        this.gotoItem(this.currentItem + this.slidesToScroll);
    }
    
    /**
     * déplace le carousel vers les élément précédent
     */
    prev() {
        this.gotoItem(this.currentItem - this.slidesToScroll);
    }

    /**
     * déplace le carousel vers l'élément ciblé
     * @param {Number} index
     */
    gotoItem(index) {
        if (index < 0) {
            if (this.options.loop) {
                index = this.items.length - this.slidesVisible;
            } else {
                return;
            }
        } else if (
            index >= this.items.length ||
            (this.items[this.currentItem + this.slidesVisible] === undefined &&
                index > this.currentItem)
        ) {
            if (this.options.loop) {
                index = 0;
            } else {
                return;
            }
        }
        let translateX = (index * -100) / this.items.length;
        this.container.style.transform =
            "translate3d(" + translateX + "%, 0, 0)";
        this.currentItem = index;
        this.moveCallbacks.forEach((cb) => cb(index));
    }


    /**
     * si la fenetre est resize, on recalcule le nombre d'éléments visible
     */
    onWindowResize() {
        let mobile = window.innerWidth < 800;
        if (mobile !== this.isMobile) {
            this.isMobile = mobile;
            this.setStyle();
            this.moveCallbacks.forEach((cb) => cb(this.currentItem));
        }
    }

    /**
     * retourne le nombre d'éléments à faire défiler (=1 si mobile)
     */
    get slidesToScroll() {
        return this.isMobile ? 1 : this.options.slidesToScroll;
    }

    /**
     *  retourne le nombre de slide visible (=1 si mobile)
     */
    get slidesVisible() {
        return this.isMobile ? 1 : this.options.slidesVisible;
    }
}
