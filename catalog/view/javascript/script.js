"use strict";
/* menu mobile */
(function () {
    var mainMenu = document.querySelector(".main-nav");
    var mainMenuItems = mainMenu.querySelectorAll(".main-nav__item");
    var burgerButton = mainMenu.querySelector(".main-nav__burger");
    var burgerButtonInner = mainMenu.querySelector(".main-nav__button");
    var mql = window.matchMedia("(max-width: 767px)");

    function mainMenuToggler(arr) {
        for (var i = 1; i < arr.length; i++) {
            mainMenuItems[i].style.marginTop = (mainMenuItems[i].style.marginTop !== "-200px") ? "-200px" : "0";
            mainMenuItems[i].style.opacity = (mainMenuItems[i].style.opacity !== "0") ? "0" : "1";
        }
    }

    function burgerButtonHandler(evt) {
        var clickedElement = evt.target;

        if (clickedElement === burgerButton || clickedElement === burgerButtonInner) {
            mainMenuToggler(mainMenuItems);
            burgerButtonInner.classList.toggle("main-nav__button--close");
        }
    }

    function setMenuVisibility() {
        for (var i = 1; i < mainMenuItems.length; i++) {
            if (mql.matches === true) {
                mainMenuItems[i].style.opacity = "1";
                mainMenuItems[i].style.marginTop = "0";
            } else {
                mainMenuItems[i].style.opacity = "0";
                mainMenuItems[i].style.marginTop = "-200px";
            }
        }
    }

    function matchMediaHandler(evt) {
        for (var i = 1; i < mainMenuItems.length; i++) {
            if (evt.matches === true) {
                mainMenuItems[i].style.opacity = "0";
                mainMenuItems[i].style.marginTop = "-200px";
                burgerButtonInner.classList.remove("main-nav__button--close");
            } else {
                mainMenuItems[i].style.opacity = "1";
                mainMenuItems[i].style.marginTop = "0";
            }
        }
    }

    setMenuVisibility();
    mql.addListener(matchMediaHandler);
    mainMenuToggler(mainMenuItems);
    burgerButton.addEventListener("click", burgerButtonHandler);
})();
/* menu mobile end */
/* bg-slider-header */
(function () {
    var mql = window.matchMedia("(max-width: 767px)");

    var slider = {
        slides: [
            "bg-header-0.jpg",
            "bg-header-1.jpg",
            "bg-header-2.jpg",
            "bg-header-3.jpg",
            "bg-header-4.jpg"
        ],

        sliderBlockHead: document.querySelector(".page-header__about"),

        getRandomFrame: function (arr) {
            var el = arr[Math.floor(Math.random() * arr.length)];
            this.frame = arr.indexOf(el);
        },

        frame: Math.floor(Math.random() * 5), // текущий кадр для отбражения - индекс картинки из массива

        set: function (image) { // установка нужного фона слайдеру
            this.sliderBlockHead.style.backgroundImage = "url(../img/" + image + ")";
        },

        init: function() { // запуск слайдера с картинкой с нулевым индексом
            this.set(this.slides[this.frame]);
        },

        right: function() { // крутим на один кадр вправо
            this.frame++;
            if(this.frame == this.slides.length) this.frame = 0;
            this.sliderBlockHead.style.transition = "all 1s ease";
            this.set(this.slides[this.frame]);
        }
    };

    function setCorrectImage() {
        if (mql.matches === true) {
            for (var i = 0; i < slider.slides.length; i++) {
                slider.slides[i] = "bg-header-" + i + ".jpg";
            }
        } else {
            for (i = 0; i < slider.slides.length; i++) {
                slider.slides[i] = "bg-header-desktop-" + i + ".jpg";
            }
        }
    }

    function matchMediaHandler(evt) {
        if (evt.matches === true) {
            for (var i = 0; i < slider.slides.length; i++) {
                slider.slides[i] = "bg-header-" + i + ".jpg";
                slider.set(slider.slides[i]);
            }
        } else {
            for (i = 0; i < slider.slides.length; i++) {
                slider.slides[i] = "bg-header-desktop-" + i + ".jpg";
                slider.set(slider.slides[i]);
            }
        }
    }

    setCorrectImage();
    mql.addListener(matchMediaHandler);
    slider.init();
    setInterval(function() { // ставим пятисекундный интервал для перелистывания картинок
        slider.right();
    }, 5000);
})();
/* bg-slider-header end */
/* slider-np */
(function () {
    var slider = document.querySelector(".slider");
    var sliderList = slider.querySelectorAll(".slider__item");
    var sliderPagination = slider.querySelectorAll(".slider__number");
    var sliderButtonLeft = slider.querySelector(".slider__button--left");
    var sliderButtonRight = slider.querySelector(".slider__button--right");


    function sliderClickHandler(evt) {
        var target = evt.target;
        // Активный итем на пагинации
        var sliderPaginationActiveItem = slider.querySelector(".slider__number--active");
        // Активная карточка товара
        var sliderListActiveItem = slider.querySelector(".slider__item--active");

        for (var i = 0; i < sliderPagination.length; i++) {
            // Если кликаем на неактивный элемент пагинации
            if (target === sliderPagination[i] && target.classList.contains("slider__number--active") === false) {
                sliderPaginationActiveItem.classList.remove("slider__number--active");
                target.classList.add("slider__number--active");
                sliderListActiveItem.classList.remove("slider__item--active");
                sliderList[i].classList.add("slider__item--active");

                // Ставим и убираем состояние disabled на управляющх стрелках(вперед . назад)
                if (target === sliderPagination[0]) {
                    sliderButtonLeft.disabled = true;
                    sliderButtonRight.disabled = false;
                } else if (target === sliderPagination[sliderPagination.length - 1]) {
                    sliderButtonRight.disabled = true;
                    sliderButtonLeft.disabled = false;
                } else {
                    sliderButtonRight.disabled = false;
                    sliderButtonLeft.disabled = false;
                }
            }
        }

        if (target === sliderButtonLeft && sliderButtonLeft.disabled === false) {
            var index = [].indexOf.call(sliderPagination, sliderPaginationActiveItem);
            var itemIndex = [].indexOf.call(sliderList, sliderListActiveItem);

            if (index === 2 && index !== -1) {
                sliderPaginationActiveItem.classList.remove("slider__number--active");
                sliderPagination[--index].classList.add("slider__number--active");
                sliderButtonRight.disabled = false;
                sliderListActiveItem.classList.remove("slider__item--active");
                sliderList[--itemIndex].classList.add("slider__item--active");
            } else if (index === 1 && index !== -1) {
                sliderPaginationActiveItem.classList.remove("slider__number--active");
                sliderPagination[--index].classList.add("slider__number--active");
                sliderButtonLeft.disabled = true;
                sliderListActiveItem.classList.remove("slider__item--active");
                sliderList[--itemIndex].classList.add("slider__item--active");
            }
        }

        if (target === sliderButtonRight && sliderButtonRight.disabled === false) {
            index = [].indexOf.call(sliderPagination, sliderPaginationActiveItem);
            itemIndex = [].indexOf.call(sliderList, sliderListActiveItem);

            if (index === 0 && index !== -1) {
                sliderPaginationActiveItem.classList.remove("slider__number--active");
                sliderPagination[++index].classList.add("slider__number--active");
                sliderButtonLeft.disabled = false;
                sliderListActiveItem.classList.remove("slider__item--active");
                sliderList[++itemIndex].classList.add("slider__item--active");
            } else if (index === 1 && index !== -1) {
                sliderPaginationActiveItem.classList.remove("slider__number--active");
                sliderPagination[++index].classList.add("slider__number--active");
                sliderButtonRight.disabled = true;
                sliderListActiveItem.classList.remove("slider__item--active");
                sliderList[++itemIndex].classList.add("slider__item--active");
            }
        }
    }

    slider.addEventListener("click", sliderClickHandler);
})();
/* slider-np end */
/* up-button */
(function () {
    var upButton = document.querySelector(".page-content__up-button");

    upButton.removeAttribute("href");

    function upButtonClickHandler() {
        if (window.pageYOffset !== 0) {
            window.scrollBy(0, -80);
            var t = setTimeout(upButtonClickHandler, 1);
        } else {
            clearTimeout(t);
        }
    }

    upButton.addEventListener("click", upButtonClickHandler);
})();
/* up-button end */
/* slider-reviews2 */
(function () {
    var slider = document.querySelectorAll(".slider")[1];
    var sliderList = slider.querySelectorAll(".slider__item");
    var sliderPagination = slider.querySelectorAll(".slider__number");
    var sliderButtonLeft = slider.querySelector(".slider__button--left");
    var sliderButtonRight = slider.querySelector(".slider__button--right");

    function sliderClickHandler(evt) {
        var target = evt.target;
        // Активный итем на пагинации
        var sliderPaginationActiveItem = slider.querySelector(".slider__number--active");
        // Активная карточка товара
        var sliderListActiveItem = slider.querySelector(".slider__item--active");

        for (var i = 0; i < sliderPagination.length; i++) {
            // Если кликаем на неактивный элемент пагинации
            if (target === sliderPagination[i] && target.classList.contains("slider__number--active") === false) {
                sliderPaginationActiveItem.classList.remove("slider__number--active");
                target.classList.add("slider__number--active");
                sliderListActiveItem.classList.remove("slider__item--active");
                sliderList[i].classList.add("slider__item--active");
                sliderListActiveItem.classList.remove("reviews__item--active");
                sliderList[i].classList.add("reviews__item--active");

                // Ставим и убираем состояние disabled на управляющх стрелках(вперед . назад)
                if (target === sliderPagination[0]) {
                    sliderButtonLeft.disabled = true;
                    sliderButtonRight.disabled = false;
                } else if (target === sliderPagination[sliderPagination.length - 1]) {
                    sliderButtonRight.disabled = true;
                    sliderButtonLeft.disabled = false;
                } else {
                    sliderButtonRight.disabled = false;
                    sliderButtonLeft.disabled = false;
                }
            }
        }

        if (target === sliderButtonLeft && sliderButtonLeft.disabled === false) {
            var index = [].indexOf.call(sliderPagination, sliderPaginationActiveItem);
            var itemIndex = [].indexOf.call(sliderList, sliderListActiveItem);

            if (index === 2 && index !== -1) {
                sliderPaginationActiveItem.classList.remove("slider__number--active");
                sliderPagination[--index].classList.add("slider__number--active");
                sliderButtonRight.disabled = false;
                sliderListActiveItem.classList.remove("slider__item--active");
                sliderList[--itemIndex].classList.add("slider__item--active");
                sliderListActiveItem.classList.remove("reviews__item--active");
                sliderList[itemIndex].classList.add("reviews__item--active");
            } else if (index === 1 && index !== -1) {
                sliderPaginationActiveItem.classList.remove("slider__number--active");
                sliderPagination[--index].classList.add("slider__number--active");
                sliderButtonLeft.disabled = true;
                sliderListActiveItem.classList.remove("slider__item--active");
                sliderList[--itemIndex].classList.add("slider__item--active");
                sliderListActiveItem.classList.remove("reviews__item--active");
                sliderList[itemIndex].classList.add("reviews__item--active");
            }
        }

        if (target === sliderButtonRight && sliderButtonRight.disabled === false) {
            index = [].indexOf.call(sliderPagination, sliderPaginationActiveItem);
            itemIndex = [].indexOf.call(sliderList, sliderListActiveItem);

            if (index === 0 && index !== -1) {
                sliderPaginationActiveItem.classList.remove("slider__number--active");
                sliderPagination[++index].classList.add("slider__number--active");
                sliderButtonLeft.disabled = false;
                sliderListActiveItem.classList.remove("slider__item--active");
                sliderList[++itemIndex].classList.add("slider__item--active");
                sliderListActiveItem.classList.remove("reviews__item--active");
                sliderList[itemIndex].classList.add("reviews__item--active");
            } else if (index === 1 && index !== -1) {
                sliderPaginationActiveItem.classList.remove("slider__number--active");
                sliderPagination[++index].classList.add("slider__number--active");
                sliderButtonRight.disabled = true;
                sliderListActiveItem.classList.remove("slider__item--active");
                sliderList[++itemIndex].classList.add("slider__item--active");
                sliderListActiveItem.classList.remove("reviews__item--active");
                sliderList[itemIndex].classList.add("reviews__item--active");
            }
        }
    }

    slider.addEventListener("click", sliderClickHandler);
})();
/* slider-reviews2 end */