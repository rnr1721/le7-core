<?php

namespace App\Core\View\Widget;

class GridViewBootstrapWidget extends AbstractWidget {

    protected array $hiddenForOrder = [];
    protected bool $ordering = false;
    protected string $orderField = '';
    protected string $orderDirection = 'asc';
    protected string $orderOption = 'asc';
    protected string $searchWord = '';
    protected string $formId = '';
    protected string $formMethod = 'GET';
    protected array $filters = [];
    protected string $widgetClasses = '';
    protected string $tableClasses = '';
    protected bool $perPage = false;
    protected bool $search = false;
    protected array $customColumns = [];
    protected array $perPageOptions = array(
        10, 30, 50, 100
    );
    protected array $actionButtons = [
        'top' => [],
        'body' => []
    ];
    protected string $base = '';
    protected bool $recordsStatistic = false;
    protected bool $paginate = false;
    protected string $title = '';

    public function render(): string {

        // Main template
        $this->htmlTemplate->setTemplate('GridView');

        $this->htmlTemplate->assign('method', $this->formMethod)
                ->assign('formId', $this->formId);

        $this->htmlTemplate->assign('searchWord', $this->searchWord);

        // Order for sorting
        $this->htmlTemplate->assign('ordering', $this->ordering)
                ->assign('orderField', $this->orderField)
                ->assign('orderDirection', $this->orderDirection)
                ->assign('hiddenForOrder', $this->hiddenForOrder);

        // Title
        $this->htmlTemplate->assign('title', $this->title);

        // Classes and Ids
        $this->htmlTemplate->assign('widgetClasses', $this->widgetClasses)
                ->assign('tableClasses', $this->tableClasses);

        // Action buttons
        $this->htmlTemplate->assign('actionButtons', $this->buildlActionLinks());

        // Search
        $this->htmlTemplate->assign('search', $this->search);

        // Pagination
        $pagination = $this->entity->getInfo()['pagination'];

        $this->htmlTemplate->assign('paginate', $this->paginate)
                ->assign('base', $this->base)
                ->assign('info', $pagination)
                ->assign('recordsStatistic', $this->recordsStatistic)
                ->assign('perPage', $this->perPage)
                ->assign('perPageOptions', $this->perPageOptions);

        // Template data
        if (empty($this->customColumns)) {
            foreach (array_keys($this->entity->getFieldLabels()) as $label) {
                $this->customColumns[$label] = null;
            }
        }
        if (!empty($this->actionButtons) && !array_key_exists('actions', $this->customColumns)) {
            $this->customColumns['actions'] = null;
        }

        $this->htmlTemplate->assign('rules', $this->entity->getRules())
                ->assign('columns', $this->entity->getFieldLabels())
                ->assign('data', $this->entity->getData())
                ->assign('customColumns', $this->customColumns);

        $this->htmlTemplate->assign('filters', $this->filters);

        return $this->htmlTemplate->compile();
    }

    public function setFormId(string $id): self {
        $this->formId = $id;
        return $this;
    }

    public function setFormMethod(string $method): self {
        $this->formMethod = $method;
        return $this;
    }

    public function setWidgetClasses(string $classes): self {
        $this->widgetClasses = $classes;
        return $this;
    }

    public function setTableClasses(string $classes): self {
        $this->tableClasses = $classes;
        return $this;
    }

    public function setSearchWord(string $searchWord): self {
        $this->searchWord = $searchWord;
        return $this;
    }

    public function setTitle(string $title): self {
        $this->title = $title;
        return $this;
    }

    public function setSearchEnable(bool $enable): self {
        $this->search = $enable;
        return $this;
    }

    public function setPerPageOptions(bool $enable, array $perPageOptions = []): self {
        $this->perPage = $enable;
        if (!empty($perPageOptions)) {
            $this->perPageOptions = $perPageOptions;
        }
        return $this;
    }

    public function setStatEnable(bool $enable): self {
        $this->recordsStatistic = $enable;
        return $this;
    }

    public function setPagerEnable(bool $enable): self {
        $this->paginate = $enable;
        return $this;
    }

    public function setBase(string $url): self {
        $this->base = $url;
        return $this;
    }

    public function setActionLinkTop(
            string $label,
            string $action = '#',
            string $class = 'btn btn-outline-primary',
            string $id = '',
            string $before = '',
            string $after = ''
    ): self {
        $this->actionButtons['top'][] = array(
            'label' => $label,
            'action' => $action,
            'class' => $class,
            'id' => $id,
            'before' => $before,
            'after' => $after
        );
        return $this;
    }

    public function setActionLinkBody(
            string $fieldId,
            string $label,
            string $action = '#',
            string $class = 'btn btn-outline-primary',
            string $id = '',
            string $before = '',
            string $after = ''
    ): self {
        $this->actionButtons['body'][] = [
            'field' => $fieldId,
            'label' => $label,
            'action' => $action,
            'class' => $class,
            'id' => $id,
            'before' => $before,
            'after' => $after
        ];
        return $this;
    }

    protected function makeActionLink(array $params): string {

        if ($params['class'] !== '') {
            $params['class'] = ' class = "' . $params['class'] . '"';
        }

        if ($params['id'] !== '') {
            $params['id'] = ' id = "' . $params['id'] . '"';
        }

        $params['action'] = str_replace('//', '/', $this->base . '/' . $params['action']);

        $result = '<a href="' . $params['action'] . '"' . $params['id'] . $params['class'] . '>' . $params['label'] . '</a>';

        return $params['before'] . $result . $params['after'];
    }

    protected function buildlActionLinks(): array {
        $result = [];
        foreach ($this->actionButtons as $key => $value) {
            if ($key === 'top') {
                foreach ($value as $ov) {
                    $result['top'][] = array(
                        'content' => $this->makeActionLink($ov)
                    );
                }
            } elseif ($key === 'body') {
                foreach ($value as $ov) {
                    $content = $this->makeActionLink($ov);
                    $result['body'][] = array(
                        'content' => $content,
                        'field' => $ov['field']
                    );
                }
            }
        }
        return $result;
    }

    public function setCustomColumns(array $columns): self {
        $this->customColumns = $columns;
        return $this;
    }

    public function setFilterFindByString(
            string $field,
            string $value,
            string $placeholder = '',
            string $class = "form-control"
    ): self {
        if (isset($this->filters[$field])) {
            return $this;
        }

        $htmlTemplate = $this->htmlTemplateFactory->getHtmlTemplateWidget();
        $htmlTemplate->setTemplate('GridViewFilterString');

        $htmlTemplate->assign('class', $class)
                ->assign('formId', $this->formId)
                ->assign('field', $field)
                ->assign('placeholder', $placeholder)
                ->assign('value', $value);

        $this->filters[$field] = $htmlTemplate->compile();
        return $this;
    }

    public function setFilterFindSelectId(
            string $field,
            array $list,
            string $current = '',
            string $class = "form-control"
    ): self {
        if (isset($this->filters[$field])) {
            return $this;
        }

        $htmlTemplate = $this->htmlTemplateFactory->getHtmlTemplateWidget();
        $htmlTemplate->setTemplate('GridViewFilterSelectId');

        $htmlTemplate->assign('field', $field)
                ->assign('formId', $this->formId)
                ->assign('class', $class)
                ->assign('list', $list)
                ->assign('current', $current);

        $this->filters[$field] = $htmlTemplate->compile();
        return $this;
    }

    protected function setFilterFindByСonds(
            string $template,
            string $field,
            string $currentValue = '',
            string $currentCond = '',
            array $list = [],
            string $classInput = "form-control",
            string $classSelect = 'form-select'
    ): self {
        if (isset($this->filters[$field])) {
            return $this;
        }

        $htmlTemplate = $this->htmlTemplateFactory->getHtmlTemplateWidget();
        $htmlTemplate->setTemplate($template);

        if (empty($list)) {
            $list = array(
                'equal' => _('Equal'),
                'not_equal' => _('Not equal'),
                'greater_than' => _('Greater than'),
                'less_then' => _('Less than'),
                'greater_or_equal' => _('Greater or equal'),
                'less_or_equal' => _('Less or equal')
            );
        }

        $htmlTemplate->assign('field', $field)
                ->assign('formId', $this->formId)
                ->assign('list', $list)
                ->assign('currentValue', $currentValue)
                ->assign('currentCond', $currentCond)
                ->assign('classInput', $classInput)
                ->assign('classSelect', $classSelect);

        $this->filters[$field] = $htmlTemplate->compile();
        return $this;
    }

    public function setFilterFindByDate(
            string $field,
            string $currentValue = '',
            string $currentCond = '',
            array $list = [],
            string $classInput = "form-control",
            string $classSelect = 'form-select'
    ): self {
        $template = 'GridViewFilterDate';
        return $this->setFilterFindByСonds($template, $field, $currentValue, $currentCond, $list, $classInput, $classSelect);
    }

    public function setFilterFindByInteger(
            string $field,
            string $currentValue = '',
            string $currentCond = '',
            array $list = [],
            string $classInput = "form-control",
            string $classSelect = 'form-select'
    ): self {
        $template = 'GridViewFilterInteger';
        return $this->setFilterFindByСonds($template, $field, $currentValue, $currentCond, $list, $classInput, $classSelect);
    }

    public function setFilterFindByHidden(
            string $field,
            string $value,
            string $valueInput,
            string $placeholder = '',
            string $class = "form-control"
    ): self {
        if (isset($this->filters[$field])) {
            return $this;
        }

        $htmlTemplate = $this->htmlTemplateFactory->getHtmlTemplateWidget();
        $htmlTemplate->setTemplate('GridViewFilterHidden');

        $htmlTemplate->assign('class', $class)
                ->assign('formId', $this->formId)
                ->assign('field', $field)
                ->assign('placeholder', $placeholder)
                ->assign('value', $value)
                ->assign('valueInput', $valueInput);

        $this->filters[$field] = $htmlTemplate->compile();
        return $this;
    }

    public function setOrder(string $field, string $direction = 'asc', array $hidden = []): self {
        $this->ordering = true;
        $this->orderField = $field;
        $this->orderDirection = $direction;
        $this->hiddenForOrder = $hidden;
        return $this;
    }

}
