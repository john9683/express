<?php

namespace GetVacancyPlugin;

use phpDocumentor\Reflection\Types\Object_;

class GetVacancyApi {
  /**
   * @var string
   */
  public $apiUrl;

  /**
   * @param int $vid
   * @return ?Object_
   */
  public function getVacancy(int $vid = 0): ?Object_ {

    $page = 0;

    do {
      $res = $this->getRes($page);
      $res_o = json_decode($res);

      if ($res !== false && is_object($res_o) && isset($res_o->objects)) {
        foreach ($res_o->objects as $key => $value) {
          if ($value->id === $vid) {
            return $value;
          }
        }
      }

      $page++;
    } while ($res_o->more);

    return null;
  }

  /**
   * @return array|null
   */
  public function getVacanciesList(): ?array {

    $ret = [];
    $page = 0;

    do {
      $res = $this->getRes($page);
      $res_o = json_decode($res);

      if ($res !== false && is_object($res_o) && isset($res_o->objects)) {
        $ret[] = $res_o->objects;
      }

      $page++;
    } while ($res_o->more);

    return count($ret) > 0 ? $ret : null;
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

  /**
   * @param $page
   * @return string
   */
  private function getRes($page): string {
    $params = "status=all&id_user="
      . $this->selfGetOption('superjob_user_id')
      . "&with_new_response=0&order_field=date&order_direction=desc&page={$page}&count=100";

    return $this->apiSend($this->apiUrl . '/hr/vacancies/?' . $params);
  }
}
