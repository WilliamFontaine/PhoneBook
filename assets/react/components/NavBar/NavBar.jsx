import React, {useEffect, useState} from "react";
import {useTranslation} from "react-i18next";
import { IoMdCloudyNight, IoMdSunny} from "react-icons/io";

import enFlag from "../../../images/flags/en.png";
import frFlag from "../../../images/flags/fr.png";
import "./NavBar.scss";

const NavBar = () => {
    const {t, i18n} = useTranslation();

    const [selectedNavItem, setSelectedNavItem] = useState(null);
    const [selectedTheme, setSelectedTheme] = useState(null);
    const [selectedLanguage, setSelectedLanguage] = useState(null);

    useEffect(() => {
        const currentPath = location.pathname.substring(1).split('/')[0];
        setSelectedNavItem(currentPath);

        setSelectedTheme(localStorage.getItem("theme") || "light");
        setSelectedLanguage(localStorage.getItem("language") || "fr");

        i18n.changeLanguage(selectedLanguage);
        document.body.classList.remove("light", "dark");
        if (selectedTheme) {
            document.body.classList.add(`${selectedTheme}-theme`);
        }
    }, [location.pathname, selectedTheme, selectedLanguage]);

    const handleChangeLanguage = (language) => {
        return () => {
            setSelectedLanguage(language);
            localStorage.setItem("language", language);
            i18n.changeLanguage(language);
        }
    }

    const handleChangeTheme = (theme) => {
        return () => {
            setSelectedTheme(theme);
            localStorage.setItem("theme", theme);
            if (theme === "light") {
                document.body.classList.remove("dark-theme");
                document.body.classList.add("light-theme");
            } else {
                document.body.classList.remove("light-theme");
                document.body.classList.add("dark-theme");
            }
        }
    }


    return (
        <nav className="navbar">
            <div className="content">
                <div className="title">
                    <p>{t("navbar.title")}</p>
                </div>
                <div className="links">
                    <a href="/contacts"
                       className={selectedNavItem === "contacts" ? "active-nav-item" : ""}
                       onClick={() => setSelectedNavItem("contacts")}
                    >
                        {t("navbar.item.contacts")}
                    </a>
                    <a href="/groups"
                       className={selectedNavItem === "groups" ? "active-nav-item" : ""}
                       onClick={() => setSelectedNavItem("groups")}
                    >
                        {t("navbar.item.groups")}
                    </a>
                </div>
            </div>


            <div className="switchers">
                <div className="theme-switcher">
                    {selectedTheme === "light"
                        ? <IoMdSunny className="icon" onClick={handleChangeTheme("dark")}/>
                        : <IoMdCloudyNight className="icon" onClick={handleChangeTheme("light")}/>
                    }
                </div>

                <div className="language-switcher">
                    {selectedLanguage === "en"
                        ? <img src={enFlag} alt="English flag" className="flag" onClick={handleChangeLanguage("fr")}/>
                        : <img src={frFlag} alt="French flag" className="flag" onClick={handleChangeLanguage("en")}/>
                    }
                </div>
            </div>
        </nav>
    );
};

export default NavBar;
