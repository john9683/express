<?php

namespace NamePlugin;

class NameApi {
  /**
   * @var string
   */
  public $apiUrl;

  /**
   * @param string $post
   * @param int $vid
   * @return array|false|mixed
   */
  public function getListVacancies(string $post, int $vid = 0): ?array {

    if (!is_object($post)) {
      return false;
    }

    $ret = [];
    $page = 0;

    do {
      $params = "status=all&id_user="
                . $this->selfGetOption('superjob_user_id')
                . "&with_new_response=0&order_field=date&order_direction=desc&page={$page}&count=100";
      $res = $this->apiSend($this->apiUrl . '/hr/vacancies/?' . $params);
      $res_o = json_decode($res);

      if ($res !== false && is_object($res_o) && isset($res_o->objects)) {

        if ($vid > 0) {
          foreach ($res_o->objects as $key => $value) {
            if ($value->id == $vid) {

              return $value;
            }
          }
        }

        $ret[] = $res_o->objects;
      }

      $page++;
    } while ($res_o->more);

    return count($ret) > 0 ? $ret : false;
  }

  /**
   * @return string
   */
  public function apiSend(): string {
    return '';
  }

  /**
   * @param string $optionName
   * @return string
   */
  public function selfGetOption(string $optionName): string {
    return '';
  }
}
