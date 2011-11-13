/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.rtt.demo.controller;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.validation.Valid;
import org.rtt.demo.domain.DemoUser;
import org.rtt.demo.domain.Property;
import org.rtt.demo.jnlp.JnlpService;
import org.rtt.demo.jnlp.JnlpUtil;
import org.rtt.demo.service.RttDemoService;
import org.rtt.demo.service.UserService;
import org.rtt.demo.util.Config;
import org.rtt.demo.util.GeneralUtil;
import org.rtt.demo.validator.DemoUserValidator;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.WebDataBinder;
import org.springframework.web.bind.annotation.InitBinder;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.servlet.ModelAndView;

/**
 *
 * @author davidwaf
 */
@Controller
public class DemoController {

    private Config config;
    private UserService userService;
    private RttDemoService rttDemoService;
    private JnlpService jnlpService;

    @Autowired
    public void setJnlpService(JnlpService jnlpService) {
        this.jnlpService = jnlpService;

    }

    @Autowired
    public void setRttDemoService(RttDemoService rttDemoService) {
        this.rttDemoService = rttDemoService;
    }

    @Autowired
    public void setUserService(UserService userService) {
        this.userService = userService;
    }

    @Autowired
    public void setConfig(Config config) {
        this.config = config;
    }

    @InitBinder
    protected void initBinder(WebDataBinder binder) {
        DemoUserValidator demoUserValidator = new DemoUserValidator();
        binder.setValidator(demoUserValidator);
    }

    @RequestMapping(value = "/index", method = RequestMethod.GET)
    public ModelAndView getIndexPage(HttpServletRequest request, HttpServletResponse response) {

        DemoUser demoUser = new DemoUser();
        Map<String, Object> model = new HashMap<String, Object>();
        model.put("demoUser", demoUser);
        return new ModelAndView("index", model);
    }

    @RequestMapping(value = "/index", method = RequestMethod.POST)
    public ModelAndView startDemo(HttpServletRequest request, HttpServletResponse response, @Valid DemoUser demoUser, BindingResult result)
            throws Exception {
        Map<String, Object> model = new HashMap<String, Object>();
        if (result.hasErrors()) {
            return new ModelAndView("index", model);

        }
        String nickName = demoUser.getNickName();
        String uri = request.getRequestURI();
        String url = request.getRequestURL().toString();
        int start = url.indexOf(uri);
        String codebase = url.substring(0, start) + "/rttdemo/client";
        String imagePath = url.substring(0, start) + "/rttdemo/client/images";
        String rawcodebase = url.substring(0, start) + "/rttdemo";
        String title = "RTT";
        String vendor = "RTT";
        String desc = "RTT";
        String ref = "rtt";
        String openfireXMPPHost = config.getProperty("xmpp.host");
        String openfireHTTPHost = config.getProperty("http.host");
        int openfireXMPPPort =Integer.parseInt( config.getProperty("xmpp.port"));
        int openfireHTTPPort = Integer.parseInt( config.getProperty("http.port"));;
        boolean enableDraw = true;
        String skinClass = "null";
        String plugins = "org.avoir.rtt.ruc.RucManager#org.avoir.rtt.whiteboard.WhiteboardManager";
        String roomName = "rttdemo";
        String domain = "localhost";
        String outboundProxy = "localhost";
        String conferenceNumber = "3100";
        int sipPort = 5060;
        int rtpPort = 8000;
        String chatWelcomeMessage = "Welcome to RTT";
        boolean debug = true;
        String jnlpPath = config.getProperty("jnlp.path");
        String baseUrl = config.getProperty("baseurl");
        String paramsBaseUrl = "/rttdemo/restservice/";
        boolean demo = false;

        String username = GeneralUtil.generateRandomStr(12);

        ArrayList<Property> properties = new ArrayList<Property>();
        properties.add(new Property("-maxstanzas", "5"));
        properties.add(new Property("-params_baseurl", paramsBaseUrl));
        properties.add(new Property("-baseurl", baseUrl));
        properties.add(new Property("-broadcastvideourl", ""));
        properties.add(new Property("-receivervideourl", ""));
        properties.add(new Property("-debug", debug + ""));
        properties.add(new Property("-admin", "true"));
        properties.add(new Property("-enabledraw", enableDraw + ""));
        properties.add(new Property("-skinclass", skinClass));
        properties.add(new Property("-httpbindhost", openfireHTTPHost));
        properties.add(new Property("-httpbindport", openfireHTTPPort + ""));
        properties.add(new Property("-serverport", openfireXMPPPort + ""));
        properties.add(new Property("-serverhost", openfireXMPPHost));
        properties.add(new Property("-plugins", plugins));
        properties.add(new Property("-username", username));
        properties.add(new Property("-names", nickName));
        properties.add(new Property("-rtpPort", rtpPort + ""));
        properties.add(new Property("-sipPort", sipPort + ""));
        properties.add(new Property("-isdemo", demo + ""));
        properties.add(new Property("-outboundProxy", outboundProxy));
        properties.add(new Property("-password", "1234"));
        properties.add(new Property("-domain", domain));
        properties.add(new Property("-userpart", "1000"));
        properties.add(new Property("-conferencenumber", conferenceNumber));
        properties.add(new Property("-roomname", roomName));
        properties.add(new Property("-chatwelcomemessage", chatWelcomeMessage));
        properties.add(new Property("-agendafilepath", ""));
        properties.add(new Property("-startupcomponent", "startup"));
        properties.add(new Property("-processid", ""));

      
        String jnlpContent = JnlpUtil.generateJnlp(
                request.getServletContext(),
                codebase,
                title,
                vendor,
                desc,
                ref,
                imagePath,
                jnlpPath,
                rawcodebase,
                properties,
                userService, jnlpService, paramsBaseUrl);
        response.setContentType("application/x-java-jnlp-file");
        response.setHeader("Content-Disposition", "attachment; filename=rttdemo.jnlp");
        response.setHeader("Content-Length", "" + jnlpContent.length());
        response.getWriter().println(jnlpContent);
        return new ModelAndView("demo", model);
    }
}
