/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.rtt.demo.validator;

import org.rtt.demo.domain.DemoUser;
import org.springframework.validation.Errors;
import org.springframework.validation.ValidationUtils;
import org.springframework.validation.Validator;

/**
 *
 * @author davidwaf
 */
public class DemoUserValidator implements Validator {

    @Override
    public boolean supports(Class<?> clazz) {
        return DemoUser.class.isAssignableFrom(clazz);
    }

    @Override
    public void validate(Object target, Errors errors) {
         ValidationUtils.rejectIfEmptyOrWhitespace(errors, "nickName", "nickname.required","Nick name required");
      
    }

    private boolean containsInvalidChars(String value) {
        String[] chars = {
            "#",
            "%",
            "^",
            "@",
            "&",
            "*",
            "(",
            ")",
            "=",
            "{",
            "}",
            "[",
            "]",
            "<",
            "?",
            "/",
            "\\"
        };


        for (int i = 0; i < chars.length; i++) {
            if (value.indexOf(chars[i]) > -1) {
                return true;
            }
        }
        return false;
    }
}
