<?php
    /**
     * @class tag_list
     * @author zero (zero@nzeo.com)
     * @brief 꼬리표 목록 출력
     * @version 0.1
     **/

    class tag_list extends WidgetHandler {

        /**
         * @brief 위젯의 실행 부분
         *
         * ./widgets/위젯/conf/info.xml 에 선언한 extra_vars를 args로 받는다
         * 결과를 만든후 print가 아니라 return 해주어야 한다
         **/
        function proc($args) {
            // 위젯 자체적으로 설정한 변수들을 체크
            $title = $args->title;
            $list_count = (int)$args->list_count;
            if(!$list_count) $list_count = 20;
            $mid_list = explode(",",$args->mid_list);

            // TagModel::getTagList()를 이용하기 위한 변수 정리
            $obj->mid = $mid_list;
            $obj->list_count = $list_count;

            // tag 모듈의 model 객체를 받아서 getTagList() method를 실행
            $oTagModel = &getModel('tag');
            $output = $oTagModel->getTagList($obj);


            // 내용을 랜던으로 정렬
            $numbers = array_keys($output->data);
            shuffle($numbers);

            // 템플릿 파일에서 사용할 변수들을 세팅
            if(count($mid_list)==1) $widget_info->module_name = $mid_list[0];
            
            $widget_info->title = $title;

            if(count($output->data)) {
                foreach($numbers as $k => $v) {
                    $widget_info->tag_list[] = $output->data[$v];
                }
            }

            Context::set('widget_info', $widget_info);

            // 템플릿의 스킨 경로를 지정 (skin, colorset에 따른 값을 설정)
            $tpl_path = sprintf('%sskins/%s', $this->widget_path, $args->skin);
            Context::set('colorset', $args->colorset);

            // 템플릿 파일을 지정
            $tpl_file = 'tags';

            // 템플릿 컴파일
            $oTemplate = &TemplateHandler::getInstance();
            return $oTemplate->compile($tpl_path, $tpl_file);
        }
    }
?>
