import React, {useEffect, useState} from "react";
import GroupsController from "../../../../controllers/groups.controller";
import Paginator from "../../../components/Paginator/Paginator";
import {useTranslation} from "react-i18next";
import Button from "../../../components/Button/Button";
import GroupCard from "../../../components/Card/GroupCard";

import "./GroupsList.scss";

const GroupsList = () => {
  const {t} = useTranslation();

  const [groups, setGroups] = useState([]);
  const [displayedGroups, setDisplayedGroups] = useState([]);


  const [currentPage, setCurrentPage] = useState(0);
  const [totalPages, setTotalPages] = useState(0);
  const [groupsPerPage] = useState(20);

  useEffect(() => {
    fetchGroups();
  }, []);

  const fetchGroups = () => {
    GroupsController.getAllGroups().then(response => {
      setGroups(response.data);
      setDisplayedGroups(response.data.slice(0, groupsPerPage));
      setTotalPages(Math.ceil(response.data.length / groupsPerPage));
    });
  }

  const handlePageChange = (page) => {
    if (page !== currentPage) {
      setCurrentPage(page);
      setDisplayedGroups(groups.slice(page * groupsPerPage, (page + 1) * groupsPerPage));
    }
  }

  return (
    <div className="groups-list">
      <div className="groups-card__header">
        <h1>{t('GroupsList.title')}</h1>
        <div className="buttons-container">
          <Button type="primary"
                  link="/groups/new"
                  content={t('GroupsList.button.new')}/>
        </div>

      </div>
      <div className="groups-list__contacts">
        {displayedGroups.map((group, index) => (
          <GroupCard key={index}
                     group={group}/>
        ))
        }
      </div>
      {groups.length === 0
        ? <p className="noContacts">{t('GroupsList.noGroups')}</p>
        : <Paginator currentPage={currentPage}
                     totalPages={totalPages}
                     onPageChange={(page) => handlePageChange(page)}/>
      }
    </div>
  )
}

export default GroupsList;
