<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\CompetitionRequirement;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DynamicFormService
{
    public function getCompetitionRequirements(Competition $competition)
    {
        $requirements = $competition->competitionRequirements()
            ->orderBy('field_group')
            ->orderBy('order_index')
            ->get()
            ->groupBy('field_group');

        return $requirements;
    }

    public function buildValidationRules(Competition $competition, array $requestData = [])
    {
        $rules = [];
        $messages = [];
        
        $requirements = $competition->competitionRequirements;
        
        foreach ($requirements as $requirement) {
            $fieldName = $requirement->field_name;
            
            if ($requirement->is_required) {
                $rules[$fieldName] = ['required'];
            } else {
                $rules[$fieldName] = ['nullable'];
            }
            
            // Add field type validation
            switch ($requirement->field_type) {
                case 'email':
                    $rules[$fieldName][] = 'email';
                    break;
                case 'number':
                    $rules[$fieldName][] = 'numeric';
                    break;
                case 'phone':
                    $rules[$fieldName][] = 'string|max:20';
                    break;
                case 'url':
                    $rules[$fieldName][] = 'url';
                    break;
                case 'file':
                    $rules[$fieldName][] = 'file';
                    $validationRules = is_string($requirement->validation_rules) ? json_decode($requirement->validation_rules, true) : $requirement->validation_rules;
                    if (isset($validationRules['max_size'])) {
                        $rules[$fieldName][] = 'max:' . $validationRules['max_size'];
                    }
                    if (isset($validationRules['mimes'])) {
                        $rules[$fieldName][] = 'mimes:' . implode(',', $validationRules['mimes']);
                    }
                    break;
                case 'text':
                case 'textarea':
                    $rules[$fieldName][] = 'string';
                    $validationRules = is_string($requirement->validation_rules) ? json_decode($requirement->validation_rules, true) : $requirement->validation_rules;
                    if (isset($validationRules['max_length'])) {
                        $rules[$fieldName][] = 'max:' . $validationRules['max_length'];
                    }
                    break;
                case 'select':
                case 'radio':
                    $fieldOptions = is_string($requirement->field_options) ? json_decode($requirement->field_options, true) : $requirement->field_options;
                    if ($fieldOptions && is_array($fieldOptions)) {
                        $options = array_keys($fieldOptions);
                        $rules[$fieldName][] = 'in:' . implode(',', $options);
                    }
                    break;
                case 'checkbox':
                    $rules[$fieldName] = ['array'];
                    $fieldOptions = is_string($requirement->field_options) ? json_decode($requirement->field_options, true) : $requirement->field_options;
                    if ($fieldOptions && is_array($fieldOptions)) {
                        $options = array_keys($fieldOptions);
                        $rules[$fieldName . '.*'] = 'in:' . implode(',', $options);
                    }
                    break;
            }
            
            // Add custom validation rules
            $validationRules = is_string($requirement->validation_rules) ? json_decode($requirement->validation_rules, true) : $requirement->validation_rules;
            if (isset($validationRules['additional_rules'])) {
                foreach ($validationRules['additional_rules'] as $rule) {
                    $rules[$fieldName][] = $rule;
                }
            }
            
            // Convert rules array to string
            $rules[$fieldName] = implode('|', $rules[$fieldName]);
            
            // Add custom messages
            if ($requirement->is_required) {
                $messages[$fieldName . '.required'] = $requirement->field_label . ' wajib diisi';
            }
        }
        
        return ['rules' => $rules, 'messages' => $messages];
    }

    public function processFormData(Competition $competition, Request $request)
    {
        $requirements = $competition->competitionRequirements;
        $processedData = [];
        
        foreach ($requirements as $requirement) {
            $fieldName = $requirement->field_name;
            $value = $request->input($fieldName);
            
            // Handle file uploads
            if ($requirement->field_type === 'file' && $request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                $path = $file->store('competition_requirements/' . $competition->id, 'public');
                $processedData[$fieldName] = $path;
            } else {
                $processedData[$fieldName] = $value;
            }
        }
        
        return $processedData;
    }

    public function generateFormHTML(Competition $competition, $existingData = [])
    {
        $requirements = $this->getCompetitionRequirements($competition);
        $html = '';
        
        foreach ($requirements as $groupName => $groupRequirements) {
            if ($groupName) {
                $html .= '<div class="form-group-header mb-3">';
                $html .= '<h6 class="text-primary">' . ucfirst($groupName) . '</h6>';
                $html .= '<hr class="mt-2">';
                $html .= '</div>';
            }
            
            foreach ($groupRequirements as $requirement) {
                $html .= $this->generateFieldHTML($requirement, $existingData);
            }
        }
        
        return $html;
    }

    private function generateFieldHTML(CompetitionRequirement $requirement, $existingData = [])
    {
        $fieldName = $requirement->field_name;
        $fieldValue = $existingData[$fieldName] ?? '';
        $required = $requirement->is_required ? 'required' : '';
        $requiredIndicator = $requirement->is_required ? '<span class="text-danger">*</span>' : '';
        
        $html = '<div class="mb-3">';
        
        switch ($requirement->field_type) {
            case 'text':
            case 'email':
            case 'phone':
            case 'url':
            case 'number':
                $type = $requirement->field_type === 'phone' ? 'tel' : $requirement->field_type;
                $html .= '<label class="form-label">' . $requirement->field_label . ' ' . $requiredIndicator . '</label>';
                if ($requirement->help_text) {
                    $html .= '<small class="form-text text-muted d-block mb-2">' . $requirement->help_text . '</small>';
                }
                $html .= '<input type="' . $type . '" class="form-control" name="' . $fieldName . '" value="' . htmlspecialchars($fieldValue) . '" ' . $required . '>';
                break;
                
            case 'textarea':
                $html .= '<label class="form-label">' . $requirement->field_label . ' ' . $requiredIndicator . '</label>';
                if ($requirement->help_text) {
                    $html .= '<small class="form-text text-muted d-block mb-2">' . $requirement->help_text . '</small>';
                }
                $html .= '<textarea class="form-control" name="' . $fieldName . '" rows="3" ' . $required . '>' . htmlspecialchars($fieldValue) . '</textarea>';
                break;
                
            case 'select':
                $html .= '<label class="form-label">' . $requirement->field_label . ' ' . $requiredIndicator . '</label>';
                if ($requirement->help_text) {
                    $html .= '<small class="form-text text-muted d-block mb-2">' . $requirement->help_text . '</small>';
                }
                $html .= '<select class="form-select" name="' . $fieldName . '" ' . $required . '>';
                $html .= '<option value="">Pilih...</option>';
                $fieldOptions = is_string($requirement->field_options) ? json_decode($requirement->field_options, true) : $requirement->field_options;
                if ($fieldOptions) {
                    foreach ($fieldOptions as $key => $label) {
                        $selected = $fieldValue == $key ? 'selected' : '';
                        $html .= '<option value="' . $key . '" ' . $selected . '>' . $label . '</option>';
                    }
                }
                $html .= '</select>';
                break;
                
            case 'radio':
                $html .= '<label class="form-label">' . $requirement->field_label . ' ' . $requiredIndicator . '</label>';
                if ($requirement->help_text) {
                    $html .= '<small class="form-text text-muted d-block mb-2">' . $requirement->help_text . '</small>';
                }
                $fieldOptions = is_string($requirement->field_options) ? json_decode($requirement->field_options, true) : $requirement->field_options;
                if ($fieldOptions) {
                    foreach ($fieldOptions as $key => $label) {
                        $checked = $fieldValue == $key ? 'checked' : '';
                        $html .= '<div class="form-check">';
                        $html .= '<input class="form-check-input" type="radio" name="' . $fieldName . '" value="' . $key . '" ' . $checked . ' ' . $required . '>';
                        $html .= '<label class="form-check-label">' . $label . '</label>';
                        $html .= '</div>';
                    }
                }
                break;
                
            case 'checkbox':
                $html .= '<label class="form-label">' . $requirement->field_label . ' ' . $requiredIndicator . '</label>';
                if ($requirement->help_text) {
                    $html .= '<small class="form-text text-muted d-block mb-2">' . $requirement->help_text . '</small>';
                }
                if ($requirement->field_options) {
                    $selectedValues = is_array($fieldValue) ? $fieldValue : [];
                    foreach ($requirement->field_options as $key => $label) {
                        $checked = in_array($key, $selectedValues) ? 'checked' : '';
                        $html .= '<div class="form-check">';
                        $html .= '<input class="form-check-input" type="checkbox" name="' . $fieldName . '[]" value="' . $key . '" ' . $checked . '>';
                        $html .= '<label class="form-check-label">' . $label . '</label>';
                        $html .= '</div>';
                    }
                }
                break;
                
            case 'file':
                $html .= '<label class="form-label">' . $requirement->field_label . ' ' . $requiredIndicator . '</label>';
                if ($requirement->help_text) {
                    $html .= '<small class="form-text text-muted d-block mb-2">' . $requirement->help_text . '</small>';
                }
                $acceptTypes = '';
                $validationRules = is_string($requirement->validation_rules) ? json_decode($requirement->validation_rules, true) : $requirement->validation_rules;
                if (isset($validationRules['mimes'])) {
                    $mimes = $validationRules['mimes'];
                    $acceptTypes = 'accept=".' . implode(',..', $mimes) . '"';
                }
                $html .= '<input type="file" class="form-control" name="' . $fieldName . '" ' . $acceptTypes . ' ' . $required . '>';
                
                // Show max file size if specified
                if (isset($validationRules['max_size'])) {
                    $maxSizeKB = $validationRules['max_size'];
                    $html .= '<small class="form-text text-muted">Maksimal ukuran file: ' . $maxSizeKB . 'KB</small>';
                }
                break;
        }
        
        $html .= '</div>';
        
        return $html;
    }
}