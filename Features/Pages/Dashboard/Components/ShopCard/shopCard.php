<?php 

    class ShopCard {

        public function __construct($shop_name, $shop_description, $shop_image, $shop_id, $href) {
            $this->shop_name = $shop_name;
            $this->shop_description = $shop_description;
            $this->shop_image = $shop_image;
            $this->shop_id = $shop_id;
            $this->href = $href;
        }

        public function render() {
            echo '
                <div class="card card-custom bg-white border-white border-0">
                    <div class="card-custom-img" style="background-image: url(' . $this->shop_image . ');"></div>
                    <div class="card-custom-avatar">
                    </div>
                    <div class="card-body" style="overflow-y: auto">
                        <h4 class="card-title">' . $this->shop_name . '</h4>
                        <p class="card-text">' . $this->shop_description . '</p>
                    </div>
                    <div class="card-footer" style="background: inherit; border-color: inherit;">
                        <a href="' . $this->href . '" class="btn btn-primary">Go to Your Shop</a>
                    </div>
                </div>
                ';
        }

    }

?>