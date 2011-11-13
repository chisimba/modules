/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.rtt.demo.jnlp;

import java.io.PrintWriter;
import java.util.List;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;

/**
 *
 * @author davidwaf
 */
@Controller
public class JnlpController {

    private JnlpService jnlpService;
    

    @Autowired
    public void setJnlpService(JnlpService jnlpService) {
        this.jnlpService = jnlpService;
    }

   @RequestMapping(value = "restservice/sjnlp/{userid}", method = RequestMethod.GET)
    public void getJnlpArgs(HttpServletRequest request, HttpServletResponse response, @PathVariable("userid") String userId) throws Exception {
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();
        List<JnlpParam> jnlpParams = jnlpService.getJnlpParams(userId);
        String args="";
        for(JnlpParam jnlpParam:jnlpParams){
            args+=jnlpParam.getKey()+"="+jnlpParam.getValue()+"!";
        }
        out.println(args);
        out.close();
    }
   
}
