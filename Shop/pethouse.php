<?php
//pethouse.ua
class Pethouse extends Sites
{
    public static function GetProduct($code = null)
    {
        require_once("Utilites/phpQuery/phpQuery.php");

        $i = 1;
        $products = array();
        $c = 0;

        do {

            $url = "https://pethouse.ua/brand/advance/page/" . $i . "/";

            $html = file_get_contents($url);
            phpQuery::newDocument($html);
            if ($i <= 1) {
                $pagesCount = pq('#tpl-testimonials-paginator-wrapper ul li:nth-last-child(2)')->length;
            }
            foreach (pq('.ph-new-catalog-tovar-block') as $product) {
                $products[$c]['url'] = pq($product)->find('a')->attr('href');
                $products[$c]['imgUrl'] = pq($product)->find('img')->attr('src');
                $products[$c]['name'] = pq($product)->find('img')->attr('alt');
                $p = 0;
                $offers = array();
                $opt = array();
                foreach (pq($product)->find('.ph-the-new-tovar-price div .tpl-wrapper-unit') as $key => $item) {
                    if ($code == pq($item)->attr('data-id')){
                        $price_offer= (pq($item)->find('.ph-new-catalog-price-block-uah')->text() . "," . pq($item)->find('.ph-new-catalog-price-block-kop')->text());
                        return $price_offer;
                    }
                    $opt[$p]['offer'] = pq($item)->find('.tpl-unit-pack')->text();
                    $opt[$p]['price'] = (pq($item)->find('.ph-new-catalog-price-block-uah')->text() . "," . pq($item)->find('.ph-new-catalog-price-block-kop')->text());
                    $p++;
                }
                $offers[$c] = $opt;
                $products[$c]['offers'] = $opt;
                $c++;
            }
            $i++;
        } while ($i <= $pagesCount);

    }
}